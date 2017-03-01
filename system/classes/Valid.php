<?php defined('SYSPATH') OR die('No direct script access.');

class Valid extends Kohana_Valid {

    /**
   	 * Checks whether a string is a valid number (negative and decimal numbers allowed).
   	 *
   	 * Uses {@link http://www.php.net/manual/en/function.localeconv.php locale conversion}
   	 * to allow decimal point to be locale specific.
   	 *
   	 * @param   string  $str    input string
   	 * @return  boolean
   	 */
   	public static function numeric($str)
   	{
   	    $str = str_replace(',','.',$str);
   	    return parent::numeric($str);
   	}
}
