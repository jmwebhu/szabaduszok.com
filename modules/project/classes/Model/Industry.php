<?php

class Model_Industry extends Model_Project_Notification_Relation
{
	protected $_table_name = 'industries';
	protected $_primary_key = 'industry_id';
	
	protected $_has_many = [
		'users' => [
			'model'         => 'User',
			'through'		=> 'users_industries',
			'far_key'		=> 'user_id',
			'foreign_key'	=> 'industry_id',
		],			
	];
	
	protected $_table_columns = [
		'industry_id'	=> ['type' => 'int', 'key' => 'PRI'],
		'name'			=> ['type' => 'string', 'null' => true],
		'slug'			=> ['type' => 'string', 'null' => true],                     
	];

    /**
     * @return string
     */
    public function getUserProjectNotificationRelationName()
    {
        return 'project_notification_industries';
    }
}