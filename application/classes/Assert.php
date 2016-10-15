<?php

class Assert
{
    public static function neverShouldReachHere()
    {
        if (self::before()) {
            return true;
        }

        throw new \Exception('Never should reach here');
    }

    public static function notNull($object)
    {
        if (self::before()) {
            return true;
        }

        if ($object == null) {
            throw new Exception('Value is null');
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