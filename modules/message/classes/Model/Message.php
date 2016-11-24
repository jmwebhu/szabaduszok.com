<?php

class Model_Message extends ORM implements Message
{
    protected $_table_name  = 'messages';
    protected $_primary_key = 'message_id';

    protected $_created_column = [
        'column' => 'created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_table_columns = [
        'message_id'    => ['type' => 'int',        'key' => 'PRI'],
        'sender_id'     => ['type' => 'int',        'null' => true],
        'receiver_id'   => ['type' => 'int',        'null' => true],
        'message'       => ['type' => 'string',     'null' => true],
        'created_at'    => ['type' => 'datetime',   'null' => true]
    ];

    protected $_belongs_to  = [
        'sender'          => [
            'model'         => 'User',
            'foreign_key'   => 'sender_id'
        ],
        'receiver'          => [
            'model'         => 'User',
            'foreign_key'   => 'receiver_id'
        ]
    ];

    public function rules()
    {
        return [
            'sender_id'     => [['not_empty']],
            'receiver_id'   => [['not_empty']],
            'message'       => [['not_empty']]
        ];
    }

    // Message implementations

    public function getId()
    {
        return $this->message_id;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getDatetime()
    {
        return new DateTime($this->created_at);
    }

    public function getJson()
    {
        return $this->getJson();
    }

    public function isReaded()
    {
        return false;
    }

    public function isArchived()
    {
        return false;
    }

    public function setSender(Message_Participant $sender)
    {
        $this->sender = $sender;
    }

    public function setReceiver(Message_Participant $receiver)
    {
        $this->receiver = $receiver;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setReaded($readed)
    {
    }

    public function setArchived($archived)
    {
    }
}