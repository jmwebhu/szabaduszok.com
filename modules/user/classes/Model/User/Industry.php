<?php

class Model_User_Industry extends ORM
{
	protected $_table_name = 'users_industries';
	
	protected $_belongs_to = [
		'user' => [
			'model'			=> 'User',
			'foreign_key'	=> 'user_id'
		],
		'industry' => [
			'model'			=> 'Industry',
			'foreign_key'	=> 'industry_id'
		],
	];
	
	protected $_table_columns = [
		'user_id'		=> ['type' => 'int'],
		'industry_id'	=> ['type' => 'int', 'null' => true],
	];
}