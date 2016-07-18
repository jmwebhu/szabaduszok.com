<?php

/**
 * class SB
 * 
 * Provides simple, static access to StringBuilder class methods
 * 
 * @author      Joó Martin <joomartin@jmweb.hu>
 * @package     Helpers
 * @version     1.0
 */

class SB
{
    public static function create($string = null)
    {
        return new \StringBuilder($string);
    }
}

