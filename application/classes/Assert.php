<?php

class Assert
{
    public static function neverShouldReachHere()
    {
        if (self::before()) {
            return true;
        }

        throw new \Exception('Failed asserting thtat never should reach here');
    }

    public static function notNull($object)
    {
        if (self::before()) {
            return true;
        }

        if ($object == null) {
            throw new Exception('Failed asserting that ' . Variable::getTypeOf($object) . ' is not NULL');
        }
    }

    public static function isTrue($condition)
    {
        if (self::before()) {
            return true;
        }

        if (!$condition) {
            throw new Exception('Failed asserting that ' . $condition . ' is TRUE');
        }
    }

    /**
     * @return bool
     */
    protected static function before()
    {
        if (Kohana::$environment == Kohana::PRODUCTION) {
            return true;
        }

        return false;
    }
}