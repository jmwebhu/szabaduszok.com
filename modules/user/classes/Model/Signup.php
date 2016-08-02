<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Signup extends Model_Auth_User
{	
	protected $_table_name = 'signups';
	protected $_primary_key = 'signup_id';		
	
	protected $_created_column = [
		'column' => 'created_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_updated_column = [
		'column' => 'updated_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_table_columns = [
		'signup_id'				=> ['type' => 'int', 'key' => 'PRI'],
		'email'					=> ['type' => 'string', 'null' => true],		
		'created_at'			=> ['type' => 'datetime', 'null' => true],
		'updated_at'			=> ['type' => 'datetime', 'null' => true],
		'type'					=> ['type' => 'int'],
	];	          
} // End Signup Model
