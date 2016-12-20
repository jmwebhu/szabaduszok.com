<?php defined('SYSPATH') OR die('No direct script access.');

class Text extends Kohana_Text 
{
	/**
	 * Ket string -et hasonlit ossze UTF8 biztosan, es kis nagybetu erzekenyseg nelkul
	 *
	 * @param string $one 		Elso string
	 * @param string $two 		Masodik string
	 *
	 * @return integer $cmp  	0 ha egyenlok, 
	 * 							-1 ha $one kisebb (ABC -ben elobb van), 
	 *							1 ha $one nagyobb (ABC -ben kesobb van) 
	 */
	public static function compareStringsUtf8SafeCaseInsensitive($one, $two)
	{
		$oneLower 	= mb_strtolower($one);
		$twoLower 	= mb_strtolower($two);
		$cmp 		= strcoll($oneLower, $twoLower);	

		return $cmp;
	}

    /**
     * @param int $length
     * @return string
     */
    public static function generatePassword($length = 6)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public static function randomString($length = 10)
    {
        return substr(str_shuffle(md5(time())), 0, $length);
    }
    
}
