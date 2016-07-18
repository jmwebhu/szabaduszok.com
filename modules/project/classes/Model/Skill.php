<?php

class Model_Skill extends ORM
{
	protected $_table_name = 'skills';
	protected $_primary_key = 'skill_id';
	
	protected $_has_many = [
		'users' => [
			'model'         => 'User',
			'through'		=> 'users_skills',
			'far_key'		=> 'user_id',
			'foreign_key'	=> 'skill_id',
		],
	];
	
	protected $_table_columns = [
		'skill_id'		=> ['type' => 'int', 'key' => 'PRI'],
		'name'			=> ['type' => 'string', 'null' => true],
		'slug'			=> ['type' => 'string', 'null' => true],
	];
	
	public function autocomplete($term)
	{	
		$skills = $this->getAll();
		$result = [];

		/**
		 * @todo REFACT ALIAS
		 */

		$tmp = AB::select(['skill_id', 'name'])->from($skills)->where('name', 'LIKE', $term)->order_by('name')->execute()->as_array();
		foreach ($tmp as $item)
		{
			$result[] = [
				'text'	=> Arr::get($item, 'name'),
				'id'  	=> Arr::get($item, 'skill_id'),
			]; 
		}			
		
		return $result;
	}
}