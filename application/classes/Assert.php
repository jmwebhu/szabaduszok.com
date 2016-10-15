<?php

class Assert
{
    public static function neverShouldReachHere()
    {
        throw new \Exception('Never should reach here');
    }
}