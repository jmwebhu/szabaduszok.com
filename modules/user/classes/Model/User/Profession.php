<?php

class Model_User_Profession extends ORM
{
	protected $_table_name = 'users_professions';
	
	protected $_belongs_to = [
		'user' => [
			'model'			=> 'User',
			'foreign_key'	=> 'user_id'
		],
		'profession' => [
			'model'			=> 'Profession',
			'foreign_key'	=> 'profession_id'
		],
	];
	
	protected $_table_columns = [
		'user_id'		=> ['type' => 'int'],
		'profession_id'	=> ['type' => 'int'],
	];
}