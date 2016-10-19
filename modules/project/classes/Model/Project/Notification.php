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
            case Model_Project::EVENT_INACTIVATE:
                $this->delete();
                break;
        }
    }

    /**
     * @param Model_Project $project
     */
    public static function addProject(Model_Project $project)
    {
        $search = Project_Notification_Search_Factory_User::makeSearch([
            'industries'    => $project->getRelationIds('industries'),
            'professions'   => $project->getRelationIds('professions'),
            'skills'        => $project->getRelationIds('skills'),
        ]);

        $users = $search->search();

        foreach ($users as $user)
        {
            /**
             * @var $user Model_User
             */
            $notification           = new Model_Project_Notification();
            $notification->user     = $user;
            $notification->project  = $project;
            $notification->save();
        }
    }

    public function send()
    {
        $limit          = Kohana::$config->load('cron')->get('notificationLimit');
        $notifications  = $this->where('is_sended', '=', 0)->limit($limit)->find_all();

        $this->sendToUsers($notifications);
    }

    /**
     * @param array $notifications
     */
    protected function sendToUsers(array $notifications)
    {
        foreach ($notifications as $notification) {
            /**
             * @var $notification    Model_Project_Notification
             */
            $html = Twig::getHtmlFromTemplate('Templates/newProjectTemplate.twig', [
                'user'      => Entity_User::createUser(Entity_User::TYPE_FREELANCER, $notification->user->user_id),
                'project'   => new Entity_Project($notification->project->project_id),
                'root'      => URL::base(true, false)
            ]);

            Email::send($notification->user->email, '[ÚJ PROJEKT]', $html);

            $notification->is_sended = 1;
            $notification->save();
        }
    }
}