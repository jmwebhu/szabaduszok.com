<?php

class Model_User_Rating extends ORM
{
	protected $_table_name = 'users_ratings';
	
	protected $_created_column = [
		'column' => 'created_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_belongs_to = [
		'rater' => [
			'model'			=> 'User',
			'foreign_key'	=> 'rater_user_id'
		],
	];
	
	protected $_table_columns = [
		'user_id'			=> ['type' => 'int', 'null' => true],
		'rater_user_id'		=> ['type' => 'int', 'null' => true],
		'rating_point' 		=> ['type' => 'int', 'null' => true],
	];
}