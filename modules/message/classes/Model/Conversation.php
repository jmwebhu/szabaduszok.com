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
}