<?php declare(strict_types=1);

namespace Dataclasses;

require_once("Utils.php");

use InvalidArgumentException;
use Generator;
use JsonSerializable;
use ReflectionClass;
use StdClass;
use Dataclasses\Utils;
use TypeError;

class Dataclass implements JsonSerializable
{
    public function __construct(array $data)
    {
        foreach ($this->get_child_instance_variables() as $property) {
            if ($this->field_has_default_value($property) && !array_key_exists($property, $data)) {
                // Use the default value
                continue;
            } elseif (!array_key_exists($property, $data)) {
                throw new InvalidArgumentException("Property $property is unexpectedly absent on the data supplied");
            }

            $this->set_property($property, $data[$property]);
        }
    }

    private function get_child_instance_variables(): Generator
    {
        $parent_vars = get_class_vars(__CLASS__);

        foreach (get_class_vars($this::class) as $property => $_) {
            if (!array_key_exists($property, $parent_vars)) {
                yield $property;
            }
        }
    }

    public function jsonSerialize(): StdClass
    {
        $obj = new stdClass();
        foreach ($this->get_child_instance_variables() as $prop) {
            $obj->$prop = $this->$prop;
        }

        return $obj;
    }

    public function __debugInfo(): array
    {
        $vars = [];
        foreach ($this->get_child_instance_variables() as $prop) {
            $vars[$prop] = $this->$prop;
        }

        return $vars;
    }

    private function parse_enum(string $name, mixed $value)
    {
        try {
            $this->{$name} = $value;
            throw new InvalidArgumentException("Unexpected error $name is expected to be of type enum");
        } catch (TypeError $e) {
            $prospective_enum = Utils\get_property_class($this, $name);
            if (enum_exists($prospective_enum)) {
                $enum = (new ReflectionClass($prospective_enum));
                if ($enum->isEnum() && $enum->hasMethod("from")) { // Backed enum
                    $enum = (new ReflectionClass($prospective_enum));
                    $fromMethod = $enum->getMethod("from");
                    return $fromMethod->invoke(null, $value);
                } else {
                    return constant("${prospective_enum}::$value");
                }
            } else {
                throw $e;
            }
        }
    }

    private function field_has_default_value(string $property): bool
    {
        return isset($this->{$property});
    }

    private function set_property(string $property, mixed $value): void
    {
        if (gettype($value) === 'array') {
            $this->{$property} = $this->handle_objects($property, $value);
        } else {
            try {
                // primitives
                $this->{$property} = $value;
            } catch (TypeError $e) {
                $this->{$property} = $this->parse_enum($property, $value);
            }
        }
    }

    private function handle_objects(string $property, mixed $value): object
    {
        $class = Utils\get_property_class($this, $property);
        if ($class === 'array') {
            throw new InvalidArgumentException("Primitive array not supported (type of $property). Use ArrayOf or DictOf");
        } else if (str_ends_with($class, ArrayOf::class)) { // TODO remove this hacky way of checking array of
            $reflection = new \ReflectionProperty($this::class, $property);
            $attributes = $reflection->getAttributes();

            foreach ($attributes as $attribute) {
                $attrObj = $attribute->newInstance();
                if (str_ends_with($attrObj::class, ArrayOf::class)) {
                    $attrObj->populate($value);
                    return $attrObj;
                }
            }
            throw new InvalidArgumentException("ArrayOf missing required rule on dataclass '" . $this::class . "'  and field $property");
        } else {
            return $this->{$property} = new $class($value);
        }
    }
}