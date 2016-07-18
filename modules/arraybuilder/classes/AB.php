<?php

/**
 * class AB
 *
 * Konnyebb, statikus hozzaferest biztosi az ArrayBuilder osztalyokhoz
 *
 * @author 		Joó Martin <joomartin@jmweb.hu>
 * @package		Core
 * @copyright	(c) 2016 Szabaduszok.com
 * @date		2017.06.04
 * @version		1.0
 * @link		https://www.dropbox.com/s/7d49u7sl7fu9cs9/Fejleszt%C3%A9si%20le%C3%ADr%C3%A1s%2C%20rendszerterv.pages?dl=0
 *
 * Pelda:
 *
 * $projects = AB::select()
 *		->from('projects')
 *		->where('search_text', 'LIKE', 'web')
 *		->order_by('name')
 *		->limit(3)->offset(1)
 *		->execute()->as_array();
 */

class AB
{
	public static function select($columns = null)
	{
		$arrayBuilderSelect = new Array_Builder_Select();		
		return $arrayBuilderSelect->select($columns);
	}
	
	public static function insert($table)
	{
		$arrayBuilderInsert = new Array_Builder_Insert();	
		return $arrayBuilderInsert->insert($table);
	}
	
	public static function update($table)
	{
		$arrayBuilderUpdate = new Array_Builder_Update();
		return $arrayBuilderUpdate->update($table);
	}
	
	public static function delete($table, $key)
	{
		$arrayBuilderDelete = new Array_Builder_Delete();
		return $arrayBuilderDelete->delete($table, $key);		
	}
}