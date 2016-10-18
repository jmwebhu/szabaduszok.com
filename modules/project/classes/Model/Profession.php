<?php

class Model_Profession extends Model_Project_Notification_Relation
{
	protected $_table_name = 'professions';
	protected $_primary_key = 'profession_id';
	
	protected $_has_many = [
		'users' => [
			'model'         => 'User',
			'through'		=> 'users_professions',
			'far_key'		=> 'user_id',
			'foreign_key'	=> 'profession_id',
		],			
	];
	
	protected $_table_columns = [
		'profession_id'	=> ['type' => 'int', 'key' => 'PRI'],
		'name'			=> ['type' => 'string', 'null' => true],
		'slug'			=> ['type' => 'string', 'null' => true],
	];

    /**
     * @return string
     */
    public function getUserProjectNotificationRelationName()
    {
        return 'project_notification_professions';
    }
}