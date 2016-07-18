<?php

class Model_Project_Skill extends ORM
{
	protected $_table_name = 'projects_skills';
	
	protected $_belongs_to = [
		'project' => [
			'model'			=> 'Project',
			'foreign_key'	=> 'project_id'
		],
		'skill' => [
			'model'			=> 'Skill',
			'foreign_key'	=> 'skill_id'
		],
	];
	
	protected $_table_columns = [
		'project_id'	=> ['type' => 'int', 'null' => true],
		'skill_id'		=> ['type' => 'int', 'null' => true],
	];
}