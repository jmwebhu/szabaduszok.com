<?php

/**
 * class Model_Project_Notification
 *
 * projects_notifications tabla ORM osztalya
 * Ez az osztaly felelos a projekt ertesitok tarolasaert es kuldeseert
 *
 * @author  Joó Martin <joomartin@jmweb.hu>
 * @link    https://docs.google.com/document/d/1vp-eK9MmS44o1XARQYg9z6nqWl1FhyErFHTObJ_Pyg8/edit#
 * @date    2016.07.08
 * @since   2.0
 * @package Core
 * @version 1.0
 */

class Model_Project_Notification extends ORM
{
	protected $_table_name 	= 'projects_notifications';
	protected $_primary_key = 'project_notification_id';	

	protected $_created_column = [
		'column' => 'created_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_updated_column = [
		'column' => 'updated_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_belongs_to = [
		'project' => [
			'model'			=> 'Project',
			'foreign_key'	=> 'project_id'
		],
		'user' => [
			'model'			=> 'User',
			'foreign_key'	=> 'user_id'
		],
	];
	
	protected $_table_columns = [
		'project_notification_id'	=> ['type' => 'int', 'null' => true],
		'project_id'				=> ['type' => 'int', 'null' => true],
		'user_id'					=> ['type' => 'int', 'null' => true],
		'is_sended'					=> ['type' => 'int', 'null' => true],
		'updated_at'				=> ['type' => 'datetime', 'null' => true],
		'created_at'				=> ['type' => 'datetime', 'null' => true]
	];

    public static function deleteAllByProject(Model_Project $project)
    {
        DB::delete('projects_notifications')->where('project_id', '=', $project->project_id)->execute();
    }
}