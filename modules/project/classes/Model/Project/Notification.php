<?php

class Model_Project_Notification extends ORM implements Observer
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

    /**
     * @param string $event
     */
    public function notify($event)
    {
        switch ($event) {
            case ORM::EVENT_INACTIVATE:
                $this->delete();
                break;
        }
    }
}