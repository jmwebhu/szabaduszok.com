<?php

class Model_Project_Partner extends ORM
{
    const TYPE_CANDIDATE    = 1;
    const TYPE_PARTICIPANT  = 2;

    /**
     * @var Model_Project_Partner_Type
     */
    protected $_type = null;

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
        'notification_id'       => ['type' => 'int',        'null' => true],
        'created_at'            => ['type' => 'datetime',   'null' => true],
        'updated_at'            => ['type' => 'datetime',   'null' => true],
        'approved_at'           => ['type' => 'datetime',   'null' => true]
    ];

    protected $_belongs_to  = [
        'user'          => [
            'model'         => 'User',
            'foreign_key'   => 'user_id'
        ],
        'project'       => [
            'model'         => 'Project',
            'foreign_key'   => 'project_id'
        ],
        'notification'  => [
            'model'         => 'Notification',
            'foreign_key'   => 'notification_id'
        ],
    ];

    /**
     * @param mixed|null $id
     */
    public function __construct($id)
    {
        parent::__construct($id);
        $this->_type = Model_Project_Partner_Type_Factory::createType($this->type);
    }


    /**
     * @param array $data
     * @return array
     */
    public function apply(array $data)
    {
        $this->throwExceptionIfEventNotPerformable(Model_Event::TYPE_CANDIDATE_NEW);

        $submit                 = $this->submit($data);
        $project                = AB::select()->from(new Model_Project())->where('project_id', '=', Arr::get($data, 'project_id'))->execute()->current();

        $notification           = Entity_Notification::createFor(Model_Event::TYPE_CANDIDATE_NEW, $project, $this->user, Arr::get($data, 'extra_data'));
        $this->notification_id  = $notification->getId();
        $this->save();

        $entity = new Entity_User_Employer($project->user);
        $entity->setNotification($notification);
        $entity->sendNotification();

        return $submit;
    }

    /**
     * @param array $extraData
     * @return array
     */
    public function undoApplication(array $extraData = [])
    {
        $this->throwExceptionIfEventNotPerformable(Model_Event::TYPE_CANDIDATE_UNDO);

        $project                = AB::select()->from(new Model_Project())->where('project_id', '=', $this->project_id)->execute()->current();
        $notification           = Entity_Notification::createFor(Model_Event::TYPE_CANDIDATE_UNDO, $project, $this->user, $extraData);

        $entity = new Entity_User_Employer($project->user);
        $entity->setNotification($notification);
        $entity->sendNotification();

        $id = $this->project_partner_id;
        $this->delete();

        return ['error' => false, 'id' => $id];
    }

    /**
     * @param array $extraData
     * @return ORM
     */
    public function approveApplication(array $extraData = [])
    {
        $this->throwExceptionIfEventNotPerformable(Model_Event::TYPE_CANDIDATE_ACCEPT);

        $this->approved_at = date('Y-m-d H:i', time());
        $this->type = self::TYPE_PARTICIPANT;
        $this->save();

        $notification           = Entity_Notification::createFor(Model_Event::TYPE_CANDIDATE_ACCEPT, $this->project, $this->user, $extraData);
        $this->notification_id  = $notification->getId();
        $this->save();

        $entity = new Entity_User_Freelancer($this->user);
        $entity->setNotification($notification);
        $entity->sendNotification();

        return ['error' => false, 'id' => $this->project_partner_id];
    }

    /**
     * @return ORM
     */
    public function rejectApplication()
    {
        $this->throwExceptionIfEventNotPerformable(Model_Event::TYPE_CANDIDATE_REJECT);

        $notification           = Entity_Notification::createFor(Model_Event::TYPE_CANDIDATE_REJECT, $this->project, $this->user);
        $this->notification_id  = $notification->getId();
        $this->save();

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
        $this->throwExceptionIfEventNotPerformable(Model_Event::TYPE_PARTICIPATE_REMOVE);

        $notification           = Entity_Notification::createFor(Model_Event::TYPE_PARTICIPATE_REMOVE, $this->project, $this->user);
        $this->notification_id  = $notification->getId();
        $this->save();

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

    /**
     * @param int $eventId
     * @throws Exception
     */
    protected function throwExceptionIfEventNotPerformable($eventId)
    {
        if ($this->_type->isEventPerformable(Model_Event_Factory::createEvent($eventId))) {
            throw new Exception('Invalid event: ' . get_class(Model_Event_Factory::createEvent($eventId))
                . ' For partner _type: ' . get_class($this->_type));
        }
    }
}