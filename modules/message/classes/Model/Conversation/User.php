<?php

class Model_Conversation_User extends ORM
{
    protected $_table_name  = 'message_interactions';
    protected $_primary_key = 'message_interaction_id';

    protected $_created_column = [
        'column' => 'created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_updated_column = [
        'column' => 'updated_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_table_columns = [
        'message_interaction_id'    => ['type' => 'int',        'key' => 'PRI'],
        'message_id'                => ['type' => 'int',        'null' => true],
        'user_id'                   => ['type' => 'int',        'null' => true],
        'is_deleted'                => ['type' => 'int',        'null' => true],
        'is_readed'                 => ['type' => 'int',        'null' => true],
        'created_at'                => ['type' => 'datetime',   'null' => true],
        'updated_at'                => ['type' => 'datetime',   'null' => true]
    ];

    public function rules()
    {
        return [
            'message_id'    => [['not_empty']],
            'user_id'       => [['not_empty']]
        ];
    }

    protected $_belongs_to  = [
        'message'          => [
            'model'         => 'Message',
            'foreign_key'   => 'message_id'
        ],
        'user'          => [
            'model'         => 'User',
            'foreign_key'   => 'user_id'
        ]
    ];
}