<?php

class Assert
{
    /**
     * @return bool
     * @throws Exception
     */
    public static function neverShouldReachHere()
    {
        if (static::before()) {
            return true;
        }

        throw new \Exception('Failed asserting that never should reach here');
    }

    /**
     * @param $object
     * @return bool
     * @throws Exception
     */
    public static function notNull($object)
    {
        if (static::before()) {
            return true;
        }

        if ($object == null) {
            throw new \Exception('Failed asserting that ' . static::getTypeOf($object) . ' is not NULL');
        }
    }

    /**
     * @param $condition
     * @return bool
     * @throws Exception
     */
    public static function isTrue($condition)
    {
        if (static::before()) {
            return true;
        }

        if (!$condition) {
            throw new \Exception("Failed asserting that {$condition} is TRUE");
        }
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     * @throws Exception
     */
    public static function equals($a, $b)
    {
        if (static::before()) {
            return true;
        }

        if ($a != $b) {
            throw new \Exception("Failed asserting that {$a} is equals to {$b}");
        }
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     * @throws Exception
     */
    public static function same($a, $b)
    {
        if (static::before()) {
            return true;
        }

        if ($a !== $b) {
            throw new \Exception("Failed asserting that {$a} is same as {$b}");
        }
    }

    /**
     * @return bool
     */
    protected static function before()
    {
        if (\Kohana::$environment == \Kohana::PRODUCTION) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $var
     * @return string
     */
    protected function getTypeOf($var)
    {
        return (is_object($var)) ? get_class($var) : gettype($var);
    }
}