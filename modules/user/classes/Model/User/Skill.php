<?php

class Model_User_Skill extends ORM
{
	protected $_table_name = 'users_skills';
	
	protected $_belongs_to = [
		'user' => [
			'model'			=> 'User',
			'foreign_key'	=> 'user_id'
		],
		'skill' => [
			'model'			=> 'Skill',
			'foreign_key'	=> 'skill_id'
		],
	];
	
	protected $_table_columns = [
		'user_id'		=> ['type' => 'int'],
		'skill_id'		=> ['type' => 'int', 'null' => true],
	];
}