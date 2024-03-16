<?php

declare(strict_types=1);

namespace YaPro\Helper;

use ReflectionClass;
use function get_class;

/**
 * @url https://gist.github.com/githubjeka/153e5a0f6d15cf20512e
 */
trait LiberatorTrait
{
    /**
     * @param object|string $object
     * @param string        $method
     * @param array|null    $args
     *
     * @return mixed
     */
    public function callClassMethod($object, $method, array $args = [])
    {
        $class = new ReflectionClass($object);
        $method = $class->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }

    /**
     * @param object|string $object
     * @param string        $propertyName
     * @param mixed         $value
     */
    public function setClassPropertyValue($object, $propertyName, $value)
    {
        $class = new ReflectionClass($object);

        while (!$class->hasProperty($propertyName) && $class->getParentClass() !== false) {
            $class = $class->getParentClass();
        }

        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    /**
     * @param object $object
     * @param string $propertyName
     *
     * @return mixed
     */
    public function getClassPropertyValue($object, $propertyName)
    {
        $class = new ReflectionClass($object);
        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @param object $object
     * @param string $method
     */
    public static function setClassMethodPublic($object, $method)
    {
        $class = new \ReflectionClass($object);
        $method = $class->getMethod($method);
        $method->setAccessible(true);
    }

    public static function getPrivate($obj, $attribute)
    {
        $getter = function () use ($attribute) {
            return $this->$attribute;
        };
        $o = \Closure::bind($getter, $obj, get_class($obj));
        return $o();
    }

    public static function setPrivate($obj, $attribute, $value)
    {
        $setter = function ($value) use ($attribute) {
            $this->$attribute = $value;
        };
        $o = \Closure::bind($setter, $obj, get_class($obj));
        $o($value);
    }
}
