<?php

class Model_Message extends ORM
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
}