<?php

class Model_Project_Profession extends ORM
{
	protected $_table_name = 'projects_professions';
	
	protected $_belongs_to = [
		'project' => [
			'model'			=> 'User',
			'foreign_key'	=> 'project_id'
		],
		'profession' => [
			'model'			=> 'Profession',
			'foreign_key'	=> 'profession_id'
		],
	];
	
	protected $_table_columns = [
		'project_id'	=> ['type' => 'int', 'null' => true],
		'profession_id'	=> ['type' => 'int', 'null' => true],
	];
}