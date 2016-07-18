<?php

/**
 * class Array_Builder_Update
 *
 * Array_Builder konkret Update tipusu alosztalya. Tombokon vegzett UPDATE tipusu lekerdezeseket kezel.
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
 * $skill = new Model_Skill();
 * 
 * $skill->name = 'photoshop';
 * $skill->slug = 'photoshop';
 * 
 * $skill->save();
 * 
 * $update = AB::update('skills')->set($skill->skill_id, $skill)->execute();
 * 
 * NINCS WHERE ZARADEK! Mivel az esetek legnagyobb reszeben csak a cache tomb x. elemet update -eljuk egy objektumra, igy a set() -ben lehet megadni az indexet es az erteket.
 *
 */

class Array_Builder_Update extends Array_Builder
{
	// WHERE viselkedes
	use Array_Builder_Trait_Where;
	
	/**
	 * UPDATE zaradek utani tabla nev
	 * 
	 * @var string $_update
	 */
	protected $_update;
	
	/**
	 * SET zaradek mezo nevek
	 * 
	 * ['key', 'value']
	 * 
	 * @var array $_set
	 */
	protected $_set = [];
	
	/**
	 * UPDATE zaradek
	 * 
	 * @param string $table		Tabla nev
	 */
	public function update($table)
	{
		$this->_update = $table;
		
		return $this;
	}
	
	/**
	 * SET zaradek
	 * 
	 * Pl.:
	 * 
	 * $skill = new Model_Skill(12);
	 * ...
	 * ...->set($skill->skill_id, $skill)->execute();
	 * 
	 * Ilyenkor a cache 12 indexebe frissiti a modelt
	 * 
	 * @param mixed 		$key	Index
	 * @param array|Object 	$index	Ertek
	 */
	public function set($key, $value)
	{
		if (!is_scalar($key))
		{
			throw new Exception('Wrong argument');
		}
		
		$this->_set = [
			'key'	=> $key,
			'value'	=> $value
		];
		
		return $this;
	}	

	/**
	 * Lekerdezes futtatasa
	 * {@inheritDoc}
	 * @see Array_Builder::execute()
	 * 
	 * @return array
	 */
	public function execute()
	{
		$items 									= Cache::instance()->get($this->_update);				
		$items[Arr::get($this->_set, 'key')] 	= Arr::get($this->_set, 'value');
		
		Cache::instance()->set($this->_update, $items);
		
		return $items[Arr::get($this->_set, 'key')];
	}
}