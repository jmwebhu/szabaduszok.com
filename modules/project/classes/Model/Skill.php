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
}