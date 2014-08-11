<?php

namespace Creekfish\Lib;

/**
 * Standard base implementation of an enumeration of values.
 *
 * Embodies a few nifty tricks:
 * - Instantiate subclass using value: $e = new MyEnum(MyEnum::CONST_NAME);
 * - Instantiate subclass using constant name: $e = new MyEnum('CONST_NAME');
 * - Instantiate subclass using factory: $e = MyEnum::CONST_NAME();
 * - Throws exception if instantiated with bad value: $e = MyEnum::UNKNOWN_CONST();  // Exception!
 * - Take advantage of type hinting: function myFunc(MyEnum $val) ...
 * - Directly compare string value constants: if (MyEnum::CONST_NAME == MyEnum::CONST_NAME()) // true!
 * - Get a list of all legal constant values: $legalValues = MyEnum::getAllowedValues();
 *
 * @author Bill Herring <arcrekfish@gmail.com>
 */
abstract class Enum
{
    private static $allowedValues;

    /**
     * Get a list of all allowed values
     * @return array
     */
    public static function getAllowedValues()
    {
        if (!isset(self::$allowedValues)) {
            self::$allowedValues = array();
        }
        if (!array_key_exists(self::getInstanceClass(), self::$allowedValues)) {
            self::$allowedValues[self::getInstanceClass()] = self::getClassConstants();
        }
        return self::$allowedValues[self::getInstanceClass()];
    }

    /**
     * The value of the enum type instance
     * @var mixed
     */
    private $value;

    /**
     * The name of the enum type instance (name of the constant)
     * @var string
     */
    private $name;

    /**
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        if (isset($value)) {
            $this->set($value);
        }
    }

    /**
     * Return the enum value
     *
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * Return the enum name (the name of the constant)
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return list of all enum names (the name of the constant)
     *
     * @return array<string, string>
     */
    public function toArray()
    {
        return self::getClassConstants();
    }

    /**
     * Return the enum value as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value2String($this->value);
    }

    /**
     * Sets the enum value
     *
     * @param mixed $value
     */
    public function set($value)
    {
        $allowed = self::getAllowedValues();

        // check values to see if this is a legal enum value
        // must use strict compare to allow for falsy values
        if (!in_array($value, $allowed, true))
        {
            // also try to set based on constant names
            if (!is_string($value)) // all const names should be strings
            {
                $this->throwException($value);
            }

            $upperValue = strtoupper($value);
            if (!array_key_exists($upperValue, $allowed)) {
                $this->throwException($value);
            }
            $value = $allowed[$upperValue];
        }

        $this->value = $value;

        // also store the constant name
        $this->name = array_search($value, $allowed); // efficient if number of constants is not insane...
    }

    private function throwException($value)
    {
        throw new \UnexpectedValueException('"' . $this->value2String($value) . '"' . ' is not a valid ' . self::getInstanceClass() . ' value');
    }

    /**
     * Returns the value as a human readable string
     *
     * @return string
     */
    private function value2String($value)
    {
        if (is_bool($value)) {
            $value = ($value) ? 'TRUE' : 'FALSE';
        }
        if (is_null($value)) {
            $value = 'NULL';
        }
        if (!is_string($value)) {
            $value = (string) $value;
        }
        return $value;
    }

    /**
     * Magic factory for Enum instances, can get new instance
     * like this: $enum = ClassName::CONST_NAME();
     *
     * @param $name
     * @param $arguments
     *
     * @return Enum instance of subclass of Enum class, initialized to CONST_NAME.
     */
    public static function __callStatic($name, $arguments)
    {
        $class = self::getInstanceClass();
        return new $class($name);
    }

    private static function getClassConstants()
    {
        $reflect = new \ReflectionClass(self::getInstanceClass());
        return $reflect->getConstants();
    }

    private static function getInstanceClass()
    {
        return get_called_class();
    }
}

