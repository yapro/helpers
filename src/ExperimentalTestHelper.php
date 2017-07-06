<?php
declare(strict_types = 1);

namespace YaPro\Helper;

/**
 * @url https://gist.github.com/githubjeka/153e5a0f6d15cf20512e
 */
class ExperimentalTestHelper
{
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

    /**
     * @param object $object
     * @param string $property
     * @return mixed
     */
    public static function getClassPropertyValue($object, $property)
    {
        $class = new \ReflectionClass($object);
        return $class->getProperty($property)->getValue($object);
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