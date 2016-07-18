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
}
