<?php

class Model_Conversation_Interaction extends ORM implements Conversation_Interaction
{
    protected $_table_name  = 'conversation_interactions';
    protected $_primary_key = 'conversation_interaction_id';

    protected $_created_column = [
        'column' => 'created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_updated_column = [
        'column' => 'updated_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_table_columns = [
        'conversation_interaction_id'   => ['type' => 'int',        'key' => 'PRI'],
        'conversation_id'               => ['type' => 'int',        'null' => true],
        'user_id'                       => ['type' => 'int',        'null' => true],
        'is_deleted'                    => ['type' => 'int',        'null' => true],
        'created_at'                    => ['type' => 'datetime',   'null' => true],
        'updated_at'                    => ['type' => 'datetime',   'null' => true]
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
        return (int)$this->conversation_interaction_id;
    }

    /**
     * @return Entity_User
     */
    public function getParticipant()
    {
        return Entity_User::createUser($this->user-type, $this->user);
    }

    /**
     * @return bool
     */
    public function getIsDeleted()
    {
        return (bool)$this->is_deleted;
    }
}
