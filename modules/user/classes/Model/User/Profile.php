<?php

class Model_User_Profile extends ORM
{
	protected $_table_name = 'users_profiles';
	
	protected $_belongs_to = [
		'user' => [
			'model'			=> 'User',
			'foreign_key'	=> 'user_id'
		],
		'profile' => [
			'model'			=> 'Profile',
			'foreign_key'	=> 'profile_id'
		],
	];
	
	protected $_table_columns = [
		'user_id'		=> ['type' => 'int'],
		'profile_id'	=> ['type' => 'int', 'null' => true],
		'url'			=> ['type' => 'string']
	];
}