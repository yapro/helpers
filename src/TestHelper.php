<?php
declare(strict_types = 1);

namespace YaPro\Helper;

class TestHelper
{
    /**
     * @param object $object
     * @param string $method
     * @param array|null $args
     * @return mixed
     */
    public static function callClassMethod($object, $method, array $args = [])
    {
        $class = new \ReflectionClass($object);
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }

    /**
     * @param object $object
     * @param string $property
     * @param mixed $value
     */
    public static function setClassPropertyValue($object, $property, $value)
    {
        $class = new \ReflectionClass($object);
        $property = $class->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}