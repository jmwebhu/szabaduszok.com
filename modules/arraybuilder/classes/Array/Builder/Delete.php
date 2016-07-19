<?php

/**
 * class Array_Builder_Delete
 *
 * Array_Builder konkret Delete tipusu alosztalya. Tombokon vegzett DELETE tipusu lekerdezeseket kezel.
 *
 * @author 		Joó Martin <joomartin@jmweb.hu>
 * @package		Core
 * @copyright	(c) 2016 Szabaduszok.com
 * @version		1.0
 * @link		https://www.dropbox.com/s/7d49u7sl7fu9cs9/Fejleszt%C3%A9si%20le%C3%ADr%C3%A1s%2C%20rendszerterv.pages?dl=0
 * @see			Array_Builder
 *
 * 2017.06.05
 * 
 * Pelda:
 * 
 * ```$delete = AB::delete('skills', 1)->execute();```
 * 
 * NINCS WHERE ZARADEK! Mivel az esetek legnagyobb reszeben csak a cache tomb x. elemet toroljuk, igy a tablenev mellett az id -t lehet megadni.
 * CSAK CACHE -BOL DOLGOZIK.
 * 
 * @todo ne csak cache -vel mukodjon, hanem sima array -vel is
 */

class Array_Builder_Delete extends Array_Builder
{
	// WHERE viselkedes
	use Array_Builder_Trait_Where;
	
	/**
	 * DELETE zaradek utani tabla nev
	 * 
	 * ['table', 'key']
	 * 
	 * @var array $_delete
	 */
	protected $_delete = [];
	
	/**
	 * DELETE zaradek
	 * 
	 * @param string $table		Tabla nev
	 * @param mixed  $key		Index
	 */
	public function delete($table, $key)
	{
		$this->_delete = [
			'table'	=> $table,
			'key'	=> $key
		];
		
		return $this;
	}	

	/**
	 * Lekerdezes futtatasa	 
	 * 
	 * @param void  
	 * @return array
	 */
	public function execute()
	{
		$items = Cache::instance()->get(Arr::get($this->_delete, 'table'));
		
		unset($items[Arr::get($this->_delete, 'key')]);
		
		Cache::instance()->set(Arr::get($this->_delete, 'table'), $items);
		
		return Arr::get($this->_delete, 'key');
	}
}