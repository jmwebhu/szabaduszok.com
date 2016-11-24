<?php

class Model_Project_Partner extends ORM
{
    const TYPE_CANDIDATE    = 1;
    const TYPE_PARTICIPANT  = 2;

    /**
     * @var Model_Project_Partner_Type
     */
    protected $_partnerType     = null;

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
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
        $this->_partnerType = Model_Project_Partner_Type_Factory::createType($type);
    }
    /**
     * @param mixed|null $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->_partnerType = Model_Project_Partner_Type_Factory::createType($this->type);
    }


    /**
     * @param array $data
     * @param Authorization_User $authorization
     * @return array
     */
    public function apply(array $data, Authorization_User $authorization)
    {
        try {
            $project                = AB::select()->from(new Model_Project())->where('project_id', '=', Arr::get($data, 'project_id'))->execute()->current();
            $entityProject          = new Entity_Project($project);

            $this->throwExceptionIfNotAuthorized($authorization->canApply());

            $result                 = $this->submit($data);
            $entityUser             = Entity_User::createUser($this->user->type, $this->user);

            $notification           = Entity_Notification::createFor(Model_Event::TYPE_CANDIDATE_NEW, $entityProject, $entityUser, Arr::get($data, 'extra_data'));
            $this->notification_id  = $notification->getId();
            $this->save();

            $entity = new Entity_User_Employer($project->user);
            $entity->setNotification($notification);
            $entity->sendNotification();

        } catch (Exception $ex) {
            Log::instance()->addException($ex);
            $result = ['error' => true, 'message' => $ex->getMessage()];
        }

        return $result;
    }

    /**
     * @param Authorization_User $authorization
     * @param array $extraData
     * @return ORM
     */
    public function undoApplication(Authorization_User $authorization, array $extraData = [])
    {
        try {
            $this->throwExceptionIfNotAuthorized($authorization->canUndoApplication());

            $entityProject          = new Entity_Project($this->project);
            $entityUser             = Entity_User::createUser($this->user->type, $this->user);

            $notification           = Entity_Notification::createFor(Model_Event::TYPE_CANDIDATE_UNDO, $entityProject, $entityUser, $extraData);

            $entity = new Entity_User_Employer($this->project->user);
            $entity->setNotification($notification);
            $entity->sendNotification();

            $result = $this->delete();

        } catch (Exception $ex) {
            Log::instance()->addException($ex);
            $result = ['error' => true, 'message' => $ex->getMessage()];
        }

        return $result;
    }

    /**
     * @param Authorization_User $authorization
     * @param array $extraData
     * @return ORM
     */
    public function approveApplication(Authorization_User $authorization, array $extraData = [])
    {
        try {
            $this->throwExceptionIfNotAuthorized($authorization->canApproveApplication());

            $this->approved_at = date('Y-m-d H:i', time());
            $this->type = self::TYPE_PARTICIPANT;
            $this->save();

            $entityProject          = new Entity_Project($this->project);
            $entityUser             = Entity_User::createUser($this->user->type, $this->user);

            $notification           = Entity_Notification::createFor(Model_Event::TYPE_CANDIDATE_ACCEPT, $entityProject, $entityUser, $extraData);
            $this->notification_id  = $notification->getId();
            $this->save();

            $entity = new Entity_User_Freelancer($this->user);
            $entity->setNotification($notification);
            $entity->sendNotification();

            $result = $this;

        } catch (Exception $ex) {
            Log::instance()->addException($ex);
            $result = ['error' => true, 'message' => $ex->getMessage()];
        }

        return $result;
    }

    /**
     * @param array $extraData
     * @return ORM
     */
    public function rejectApplication(Authorization_User $authorization, array $extraData = [])
    {
        try {
            $this->throwExceptionIfNotAuthorized($authorization->canRejectApplication());
            $entityProject          = new Entity_Project($this->project);
            $entityUser             = Entity_User::createUser($this->user->type, $this->user);

            $notification           = Entity_Notification::createFor(Model_Event::TYPE_CANDIDATE_REJECT, $entityProject, $entityUser, $extraData);
            $this->notification_id  = $notification->getId();
            $this->save();

            $entity = new Entity_User_Freelancer($this->user);
            $entity->setNotification($notification);
            $entity->sendNotification();

            $result = $this->delete();

        } catch (Exception $ex) {
            Log::instance()->addException($ex);
            $result = ['error' => true, 'message' => $ex->getMessage()];
        }

        return $result;
    }

    /**
     * @param array $extraData
     * @return ORM
     */
    public function cancelParticipation(Authorization_User $authorization, array $extraData = [])
    {
        try {
            $this->throwExceptionIfNotAuthorized($authorization->canCancelParticipation());
            $entityProject          = new Entity_Project($this->project);
            $entityUser             = Entity_User::createUser($this->user->type, $this->user);

            $notification           = Entity_Notification::createFor(Model_Event::TYPE_PARTICIPATE_REMOVE, $entityProject, $entityUser, $extraData);
            $this->notification_id  = $notification->getId();
            $this->save();

            $entity = new Entity_User_Freelancer($this->user);
            $entity->setNotification($notification);
            $entity->sendNotification();

            $result = $this->delete();

        } catch (Exception $ex) {
            Log::instance()->addException($ex);
            $result = ['error' => true, 'message' => $ex->getMessage()];
        }

        return $result;
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

        if (!Arr::get($data, 'user_id')) {
            $data['user_id'] = Auth::instance()->get_user()->user_id;
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
     * @param bool $isAuthorized
     * @throws Exception
     */
    protected function throwExceptionIfNotAuthorized($isAuthorized)
    {
        if (!$isAuthorized) {
            throw new Exception('Nincs jogosultságod a művelethez');
        }
    }

    /**
     * @return string
     */
    public function getTypePlural()
    {
        return $this->_partnerType->getTypePlural();
    }

    /**
     * @param Model_User $user
     * @param Model_Project $project
     * @return bool
     */
    public static function isUserCandidateInProject(Model_User $user, Model_Project $project)
    {
        return self::isUserPartnerInProject($user, $project, self::TYPE_CANDIDATE);
    }

    /**
     * @param Model_User $user
     * @param Model_Project $project
     * @return bool
     */
    public static function isUserParticipateInProject(Model_User $user, Model_Project $project)
    {
        return self::isUserPartnerInProject($user, $project, self::TYPE_PARTICIPANT);
    }

    /**
     * @param Model_User $user
     * @param Model_Project $project
     * @param $type
     * @return bool
     */
    protected static function isUserPartnerInProject(Model_User $user, Model_Project $project, $type)
    {
        $count = DB::select()
            ->from('projects_partners')
            ->where('user_id', '=', $user->user_id)
            ->and_where('project_id', '=', $project->project_id)
            ->and_where('type', '=', $type)
            ->execute()->count();

        return $count == 1;
    }
}