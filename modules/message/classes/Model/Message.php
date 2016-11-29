<?php

class Model_Message extends ORM implements Message
{
    protected $_table_name  = 'messages';
    protected $_primary_key = 'message_id';

    protected $_created_column = [
        'column' => 'created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_updated_column = [
        'column' => 'updated_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_table_columns = [
        'message_id'        => ['type' => 'int',        'key' => 'PRI'],
        'conversation_id'   => ['type' => 'int',        'null' => true],
        'sender_id'         => ['type' => 'int',        'null' => true],
        'message'           => ['type' => 'string',     'null' => true],
        'created_at'        => ['type' => 'datetime',   'null' => true],
        'updated_at'        => ['type' => 'datetime',   'null' => true]
    ];

    protected $_belongs_to  = [
        'sender'          => [
            'model'         => 'User',
            'foreign_key'   => 'sender_id'
        ],
        'conversation'          => [
            'model'         => 'User',
            'foreign_key'   => 'sender_id'
        ],
    ];

    public function rules()
    {
        return [
            'conversation_id'   => [['not_empty']],
            'sender_id'         => [['not_empty']],
            'message'           => [['not_empty']]
        ];
    }

    // Message implementaions

    /**
     * @return int
     */
    public function getId()
    {
        return $this->message_id;
    }

    /**
     * @return Entity_User
     */
    public function getSender()
    {
        return Entity_User::createUser($this->sender->type, $this->sender);
    }

    /**
     * @return Entity_Conversation
     */
    public function getConversation()
    {
        return new Entity_Conversation($this->conversation);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
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

    public function send()
    {
        // TODO: Implement send() method.
    }
}