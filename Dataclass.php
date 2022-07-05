<?php declare(strict_types=1);

namespace Dataclasses;

require_once("Utils.php");

use InvalidArgumentException;
use Generator;
use JsonSerializable;
use ReflectionClass;
use StdClass;
use Dataclasses\Utils;

class Dataclass implements JsonSerializable {
    public function __construct(array $data) {
        foreach($this->get_child_instance_variables() as $property) {
            if (!array_key_exists($property, $data)) {
                throw new InvalidArgumentException("Property $property is unexpectedly absent on the data supplied");
            }
            switch (gettype($data[$property])) {
                case 'array': // Handle arrays and objects
                    /*$class = Utils\get_object_var_class($this, $property);
                    if ($class === 'array') {
                        $this->{$property} = $data[$property];
                    } else {
                        $this->{$property} = new $class($data[$property]);
                    }
                    break;*/
                default: // Handle primitives and enums
                    try {
                        $this->{$property} = $data[$property];
                    }
                    catch(\TypeError $e)
                    {

                        $prospective_enum = Utils\last_word($e->getMessage());
                        if(enum_exists($prospective_enum)) {
                            $enum = (new ReflectionClass($prospective_enum));
                            if($enum->hasMethod("from")) { // Backed enum
                                $enum = (new ReflectionClass($prospective_enum));
                                $fromMethod = $enum->getMethod("from");
                                $this->{$property} = $fromMethod->invoke(null, $data[$property]);
                            }
                            else{
                                $this->{$property} = constant("${prospective_enum}::${data[$property]}");
                            }
                        }
                        else{
                            throw $e;
                        }
                    }
            }
        }
    }

    private function get_child_instance_variables(): Generator {
        $parent_vars = get_class_vars(__CLASS__);

        foreach(get_class_vars($this::class) as $property => $_) {
            if (!array_key_exists($property, $parent_vars)) {
                yield $property;
            }
        }
    }

    public function jsonSerialize(): StdClass {
        $obj = new stdClass();
        foreach($this->get_child_instance_variables() as $prop) {
            $obj->$prop = $this->$prop;
        }

        return $obj;
    }

    public function __debugInfo(): array {
        $vars = [];
        foreach($this->get_child_instance_variables() as $prop) {
            $vars[$prop] = $this->$prop;
        }

        return $vars;
    }
}

