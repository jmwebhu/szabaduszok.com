<?php

class Model_User_Project_Notification extends ORM
{
	const RELATION_ANY = 1;
	const RELATION_ALL = 2;
	
	protected $_table_name = 'users_project_notification';
	protected $_primary_key = 'users_project_notification_id';
	
	protected $_created_column = [
		'column' => 'created_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_updated_column = [
		'column' => 'updated_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_belongs_to = [
		'user' => [
			'model'         => 'User',
			'foreign_key'   => 'user_id'
		],
		'industry' => [
			'model'         => 'Industry',
			'foreign_key'   => 'industry_id'
		],
		'profession' => [
			'model'         => 'Profession',
			'foreign_key'   => 'profession_id'
		],
		'skill' => [
			'model'         => 'Skill',
			'foreign_key'   => 'skill_id'
		],
	];
}