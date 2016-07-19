<?php

/**
 * class AB
 *
 * Konnyebb, statikus hozzaferest biztosi az Array_Builder osztalyokhoz
 *
 * @author 		Joó Martin <joomartin@jmweb.hu>
 * @package		Core
 * @copyright	(c) 2016 Szabaduszok.com
 * @version		1.0
 * @link		https://www.dropbox.com/s/7d49u7sl7fu9cs9/Fejleszt%C3%A9si%20le%C3%ADr%C3%A1s%2C%20rendszerterv.pages?dl=0
 *
 * 2017.06.04
 * 
 * Pelda:
 *
 * ```$projects = AB::select()
 *		->from('projects')
 *		->where('search_text', 'LIKE', 'web')
 *		->order_by('name')
 *		->limit(3)->offset(1)
 *		->execute()->as_array();```
 * 
 * Bovebb leiras a peldanyositott osztalyoknal talalhato!
 */

class AB
{
	/**
	 * SELECT zaradek
	 * 
	 * @param array|string $columns		Mezo nevek
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Select::select()
	 */
	public static function select($columns = null)
	{
		$arrayBuilderSelect = new Array_Builder_Select();		
		return $arrayBuilderSelect->select($columns);
	}
	
	/**
	 * INSERT zaradek
	 * 
	 * @param string $table		Table nev
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Insert::insert()
	 */
	public static function insert($table)
	{
		$arrayBuilderInsert = new Array_Builder_Insert();	
		return $arrayBuilderInsert->insert($table);
	}
	
	/**
	 * UPDATE zaradek
	 * 
	 * @param string $table		Table nev
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Update::update()
	 */
	public static function update($table)
	{
		$arrayBuilderUpdate = new Array_Builder_Update();
		return $arrayBuilderUpdate->update($table);
	}
	
	/**
	 * DELETE zaradek
	 * 
	 * @param string $table	Tabla nev
	 * @param string $key	Index
	 * 
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Delete::delete()
	 */
	public static function delete($table, $key)
	{
		$arrayBuilderDelete = new Array_Builder_Delete();
		return $arrayBuilderDelete->delete($table, $key);		
	}
}