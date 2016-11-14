<?php

/**
 * class Array_Builder_Insert
 *
 * Array_Builder konkret Insert tipusu alosztalya. Tombokon vegzett INSERT tipusu lekerdezeseket kezel.
 *
 * @author 		Joó Martin <joomartin@jmweb.hu>
 * @package		Core
 * @copyright	(c) 2016 Szabaduszok.com
 * @date		2017.06.05
 * @version		1.0
 * @link		https://www.dropbox.com/s/7d49u7sl7fu9cs9/Fejleszt%C3%A9si%20le%C3%ADr%C3%A1s%2C%20rendszerterv.pages?dl=0
 * @see			Array_Builder
 *
 * Pelda:
 *
 * ```$projects = AB::insert('projects')->set($keys)->values($values)->execute();```
 *
 */

class Array_Builder_Insert extends Array_Builder
{
	/**
	 * INSERT zaradek utani tabla nev
	 *
	 * @var string $_insert
	 */
	protected $_insert;

	/**
	 * SET zaradek mezo nevek
	 *
	 * @var array $_set
	 */
	protected $_set = [];

	/**
	 * VALUES zaradek mezo ertekek
	 *
	 * @var array $_values
	 */
	protected $_values = [];

	/**
	 * INSERT zaradek
	 *
	 * @param string $table		Tabla nev
	 */
	public function insert($table)
	{
		$this->_insert = $table;

		return $this;
	}

	/**
	 * SET zaradek
	 *
	 * Csak string -et lehet megadni. Ez lesz a cache -ben az index, es azon az indexen fog
	 * elhelyezkedni a tenyleges ertek.
	 *
	 * Pl.:
	 *
	 * ```$skill = new Model_Skill(12);
	 *    ...
	 *	->set($skill->skill_id)->values($skill);```
	 *
	 * Ilyenkor a cache 12 indexebe szurja be a modelt
	 *
	 * @param array $index	Index
	 * @return Array_Builder
	 */
	public function set($index)
	{
		if (!is_scalar($index))
		{
			throw new Exception('Wrong argument. Type of ' . gettype($index));
		}

		$this->_set = $index;

		return $this;
	}

	/**
	 * VALUES zaradek
	 *
	 * @param array|Object $values		Ertekek
	 * @return Array_Builder
	 */
	public function values($values)
	{
		$this->_values = $values;

		return $this;
	}

	/**
	 * Lekerdezes futtatasa
	 * @see Array_Builder::execute()
	 *
	 * @param void
	 * @return array
	 */
	public function execute()
	{
		$items 				= Cache::instance()->get($this->_insert);
		$items[$this->_set] = $this->_values;

		Cache::instance()->set($this->_insert, $items);

		return $items[$this->_set];
	}
}
