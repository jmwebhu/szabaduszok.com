<?php

class Model_Conversation extends ORM implements Conversation
{
    protected $_table_name  = 'conversations';
    protected $_primary_key = 'conversation_id';

    protected $_created_column = [
        'column' => 'created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_updated_column = [
        'column' => 'updated_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_table_columns = [
        'conversation_id'   => ['type' => 'int',        'key' => 'PRI'],
        'name'              => ['type' => 'string',     'null' => true],
        'slug'              => ['type' => 'string',     'null' => true],
        'created_at'        => ['type' => 'datetime',   'null' => true],
        'updated_at'        => ['type' => 'datetime',   'null' => true]
    ];

    protected $_has_many = [
        'messages'  => [
            'model'         => 'Message',
            'foreign_key'   => 'conversation_id'
        ],
        'users'  => [
            'model'         => 'User',
            'far_key'       => 'user_id',
            'through'       => 'conversations_users',
            'foreign_key'   => 'conversation_id'
        ],
        'interactions'  => [
            'model'         => 'Conversation_Interaction',
            'foreign_key'   => 'conversation_id'
        ],
    ];

    public function rules()
    {
        return [
            'name'  => [['not_empty']]
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->conversation_id;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return new DateTime($this->created_at);
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return new DateTime($this->updated_at);
    }

    /**
     * @return array of Conversation_Participant
     */
    public function getParticipants()
    {
        $users = [];
        foreach ($this->users->find_all as $user) {
            $users[] = Entity_User::createUser($user->type, $user);
        }

        return $users;
    }

    /**
     * @return array of Message
     */
    public function getMessages()
    {
        $entity = new Entity_Message();
        return $entity->getEntitiesFromModels($this->messages->find_all());
    }

    /**
     * @param array $data
     */
    public function submit(array $data)
    {
        $entities = [];
        foreach ($data['users'] as $id) {
            $model      = new Model_User($id);
            $entities[] = Entity_User::createUser($model->type, $model);
        }

        $data['name'] = Text_Conversation::getNameFromUsers($entities);

        parent::submit($data);

        $this->saveSlug();
        $this->addRelations($data);
    }

    /**
     * Visszaadja az uzenetek oldal bal oldali paneleben megjeleno beszelgeteseket.
     * Azokat, amikben szerepel a felasznalo, ES nincs hozza torolt interacio
     *
     * @param int $userId
     * @return array
     */
    public function getForLeftPanelBy($userId)
    {
        $model          = new Model_Conversation();
        $allByUser      = $model->getAllBy($userId);
        $forLeftPanel   = [];

        foreach ($allByUser as $item) {
            if (!$item->hasDeletedInteractionBy($userId)) {
                $forLeftPanel[] = $item;
            }
        }

        return $forLeftPanel;
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getAllBy($userId)
    {
        $model = new Model_Conversation();

        return $model
            ->join('conversations_users', 'left')
                ->on('conversations_users.conversation_id', '=', 'conversation.conversation_id')

            ->where('conversations_users.user_id', '=', $userId)
            ->find_all();
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function hasDeletedInteractionBy($userId)
    {
        return ($this->interactions->where('user_id', '=', $userId)->and_where('is_deleted', '=', 1)->count_all() > 0);
    }

    /**
     * @param array $data
     */
    protected function addRelations(array $data)
    {
        $this->removeAll('conversations_users', 'conversation_id');
        $this->addRelation($data, new Model_Conversation_User(), new Model_User());
    }
}