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
	
	protected $_table_columns = [
		'users_project_notification_id'	=> ['type' => 'int', 'key' => 'PRI'],
		'user_id'						=> ['type' => 'int', 'null' => true],
		'industry_id' 					=> ['type' => 'int', 'null' => true],
		'profession_id' 				=> ['type' => 'int', 'null' => true],
		'skill_id' 						=> ['type' => 'int', 'null' => true],
		'created_at' 					=> ['type' => 'datetime', 'null' => true],
		'updated_at' 					=> ['type' => 'datetime', 'null' => true],
	];
	
	/**
	 * Visszaadja a kepesseg azonositokat a kapott felhasznalohoz
	 * 
	 * @param int $userId			Felhasznalo azonosito
	 * @return array $skillIds		Kepesseg azonositok
	 */
	public function getSkillIdsByUser($userId)
	{
		$notifications = $this->where('user_id', '=', $userId)->and_where('skill_id', 'IS NOT', null)->find_all();
		$skillIds = [];
		
		foreach ($notifications as $notification)
		{
			/**
			 * @var $notification Model_User_Project_Notification
			 */
			$skillIds[] = $notification->skill_id;
		}	
		
		return $skillIds;
	}

	public static function createBy(Model_Project $project)
    {
        $search = Search_Factory_User::makeSearch();
    }
}