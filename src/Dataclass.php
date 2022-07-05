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

class Dataclass implements JsonSerializable {
    public function __construct(array $data) {
        foreach($this->get_child_instance_variables() as $property) {
            if (!array_key_exists($property, $data) && isset($this->{$property})) {
                continue;
            }
            elseif(!array_key_exists($property, $data)) {
                throw new InvalidArgumentException("Property $property is unexpectedly absent on the data supplied");
            }
            switch (gettype($data[$property])) {
                case 'array': // Handle arrays and objects
                    $class = Utils\get_property_class($this, $property);
                    if ($class === 'array') {
                        $this->{$property} = $data[$property];
                    } else {
                        $this->{$property} = new $class($data[$property]);
                    }
                    break;
                default: // Handle primitives and enums
                    try {
                        $this->{$property} = $data[$property];
                    }
                    catch(TypeError $e)
                    {
                        $this->_handleEnums($property, $data[$property]);
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

    private function _handleEnums(string $name, mixed $value): void
    {
        try {
           $this->{$name} = $value;
        }
        catch (TypeError $e)
        {
            $prospective_enum = Utils\get_property_class($this, $name);
            if(enum_exists($prospective_enum)) {
                $enum = (new ReflectionClass($prospective_enum));
                if($enum->isEnum() && $enum->hasMethod("from")) { // Backed enum
                    $enum = (new ReflectionClass($prospective_enum));
                    $fromMethod = $enum->getMethod("from");
                    $this->{$name} = $fromMethod->invoke(null, $value);
                }
                else{
                    $this->{$name} = constant("${prospective_enum}::$value");
                }
            }
            else{
                throw $e;
            }
        }
    }
}

//

if (!count(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)))
{
    print("<pre>");
    $json_str = file_get_contents("generated.json");

    print("The size of the read file is " . strlen($json_str) / 1024 . "KB\n");

    $start_time = microtime(true);
    $arr = json_decode($json_str, true);
    $end_time = microtime(true);
    print("Took " . $end_time - $start_time . "s to parse the data into an array\n");

    /**  **/
    $start_time = microtime(true);
    $arr = json_decode($json_str);
    $end_time = microtime(true);
    print("Took " . $end_time - $start_time . "s to parse the data into StdClasses\n");

    /**  **/
    $arr = json_decode($json_str, true);
    $start_time = microtime(true);
    foreach($arr as $data)
    {
        new Sample($data);
    }
    $end_time = microtime(true);

    print("Took " . $end_time - $start_time . "s to parse the data into a dataclass");

    print("</pre>");

    print("Hello world");
    var_dump(new Sample(['i' => 15, 'j' => 9]));
}