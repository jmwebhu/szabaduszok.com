<?php

class Variable extends Kohana_Variable
{
    public static function getTypeOf($var)
    {
        return (is_object($var)) ? get_class($var) : gettype($var);
    }
}