<?php

class Model_Profession extends ORM
{
	protected $_table_name = 'professions';
	protected $_primary_key = 'profession_id';
	
	protected $_has_many = [
		'users' => [
			'model'         => 'User',
			'through'		=> 'users_professions',
			'far_key'		=> 'user_id',
			'foreign_key'	=> 'profession_id',
		],			
	];
	
	protected $_table_columns = [
		'profession_id'	=> ['type' => 'int', 'key' => 'PRI'],
		'name'			=> ['type' => 'string', 'null' => true],
		'slug'			=> ['type' => 'string', 'null' => true],
	];
	
	public function likeName($name)
	{
		return $this->where('name', 'LIKE', '%' . $name . '%');
	}
	
	public function byName($name)
	{
		return $this->where('name', '=', $name);
	}
	
	public function autocomplete($term)
	{
		$professions = $this->getAll();
		$result = [];

		/**
		 * @todo REFACT ALIAS
		 */

		$tmp = AB::select(['profession_id', 'name'])->from($professions)->where('name', 'LIKE', $term)->order_by('name')->execute()->as_array();
		foreach ($tmp as $item)
		{
			$result[] = [
				'text'	=> Arr::get($item, 'name'),
				'id'  	=> Arr::get($item, 'profession_id'),
			]; 
		}
	
		return $result;
	}
}