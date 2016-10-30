<?php

class Model_Project_Partner extends ORM
{
    const TYPE_CANDIDATE    = 1;
    const TYPE_PARTICIPANT  = 2;

    protected $_table_name  = 'projects_partners';
    protected $_primary_key = 'project_partner_id';

    protected $_created_column = [
        'column' => 'created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_updated_column = [
        'column' => 'updated_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_table_columns = [
        'project_partner_id'    => ['type' => 'int',        'key' => 'PRI'],
        'user_id'               => ['type' => 'int',        'null' => true],
        'project_id'            => ['type' => 'int',        'null' => true],
        'type'                  => ['type' => 'int',        'null' => true],
        'created_at'            => ['type' => 'datetime',   'null' => true],
        'updated_at'            => ['type' => 'datetime',   'null' => true],
        'approved_at'           => ['type' => 'datetime',   'null' => true]
    ];

    protected $_belongs_to  = [
        'user'          => [
            'model'         => 'User',
            'foreign_key'   => 'user_id'
        ],
        'project'          => [
            'model'         => 'Project',
            'foreign_key'   => 'project_id'
        ]
    ];

    /**
     * @param array $data
     * @return array
     */
    public function apply(array $data)
    {
        $submit = $this->submit($data);
        $project = AB::select()->from(new Model_Project())->where('project_id', '=', Arr::get($data, 'project_id'))->execute()->current();

        $notification = Entity_Notification::createFor(Model_Event::TYPE_CANDIDATE_NEW, $project, $this->user);

        $entity = new Entity_User_Employer($project->user);
        $entity->setNotification($notification);
        $entity->sendNotification();

        return $submit;
    }

    /**
     * @return ORM
     */
    public function undoApplication()
    {
        $project = AB::select()->from(new Model_Project())->where('project_id', '=', $this->project_id)->execute()->current();

        $notification = Entity_Notification::createFor(Model_Event::TYPE_CANDIDATE_UNDO, $project, $this->user);

        $entity = new Entity_User_Employer($project->user);
        $entity->setNotification($notification);
        $entity->sendNotification();

        return $this->delete();
    }

    /**
     * @return ORM
     */
    public function approveApplication()
    {
        $this->approved_at = date('Y-m-d H:i', time());
        $this->type = self::TYPE_PARTICIPANT;
        $save = $this->save();

        $notification = Entity_Notification::createFor(Model_Event::TYPE_CANDIDATE_ACCEPT, $this->project, $this->user);

        $entity = new Entity_User_Freelancer($this->user);
        $entity->setNotification($notification);
        $entity->sendNotification();

        return $save;
    }

    /**
     * @return ORM
     */
    public function rejectApplication()
    {
        $notification = Entity_Notification::createFor(Model_Event::TYPE_CANDIDATE_REJECT, $this->project, $this->user);

        $entity = new Entity_User_Freelancer($this->user);
        $entity->setNotification($notification);
        $entity->sendNotification();

        return $this->delete();
    }

    /**
     * @return ORM
     */
    public function cancelParticipation()
    {
        $notification = Entity_Notification::createFor(Model_Event::TYPE_PARTICIPATE_REMOVE, $this->project, $this->user);

        $entity = new Entity_User_Freelancer($this->user);
        $entity->setNotification($notification);
        $entity->sendNotification();

        return $this->delete();
    }

    /**
     * @param array $data
     * @return array
     */
    public function submit(array $data)
    {
        if (!Arr::get($data, 'type')) {
            $data['type'] = self::TYPE_CANDIDATE;
        }

        return parent::submit($data);
    }

    /**
     * @param array $data
     * @return ORM
     */
    public function getByUserProject(array $data)
    {
        return $this->where('user_id', '=', $data['user_id'])->and_where('project_id', '=', $data['project_id'])->limit(1)->find();
    }
}