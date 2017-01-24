<?php

class Model_Conversation_User extends ORM implements Conversation_Participant_Link
{
    protected $_table_name  = 'conversations_users';
    protected $_primary_key = 'conversation_user_id';

    protected $_table_columns = [
        'conversation_user_id'  => ['type' => 'int',        'key' => 'PRI'],
        'conversation_id'       => ['type' => 'int',        'null' => true],
        'user_id'               => ['type' => 'int',        'null' => true]
    ];

    public function rules()
    {
        return [
            'conversation_id'   => [['not_empty']],
            'user_id'           => [['not_empty']]
        ];
    }

    protected $_load_with = [
        'conversation', 'user'
    ];

    protected $_belongs_to  = [
        'conversation'          => [
            'model'         => 'Conversation',
            'foreign_key'   => 'conversation_id'
        ],
        'user'          => [
            'model'         => 'User',
            'foreign_key'   => 'user_id'
        ]
    ];

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->conversation_user_id;
    }

    /**
     * @return Entity_Conversation
     */
    public function getConversation()
    {
        return new Entity_Conversation($this->conversation);
    }

    /**
     * @return Entity_User
     */
    public function getParticipant()
    {
        return Entity_User::createUser($this->user->type, $this->user);
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
}
