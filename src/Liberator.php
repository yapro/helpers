<?php

declare(strict_types=1);

namespace YaPro\Helper;

use ReflectionClass;

/**
 * @author Torge Kummerow
 *
 * Example:
 *
 * $myObject = new Liberator(new MyObject());
 *
 * Writing to a private field:
 * $myObject->somePrivateField = "testData";
 *
 * Reading a private field:
 * echo $myObject->somePrivateField;
 *
 * Calling a private function:
 * $result = $myObject->somePrivateFunction($arg1, $arg2);
 */
class Liberator
{
    private $originalObject;
    private $class;

    public function __construct($originalObject)
    {
        $this->originalObject = $originalObject;
        $this->class = new ReflectionClass($originalObject);
    }

    public function __get($name)
    {
        $property = $this->class->getProperty($name);
        $property->setAccessible(true);

        return $property->getValue($this->originalObject);
    }

    public function __set($name, $value)
    {
        $property = $this->class->getProperty($name);
        $property->setAccessible(true);
        $property->setValue($this->originalObject, $value);
    }

    public function __call($name, $args)
    {
        $method = $this->class->getMethod($name);
        $method->setAccessible(true);

        return $method->invokeArgs($this->originalObject, $args);
    }
}
