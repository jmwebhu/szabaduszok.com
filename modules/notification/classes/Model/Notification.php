<?php

class Model_Notification extends ORM
{
    protected $_table_name  = 'notifications';
    protected $_primary_key = 'notification_id';

    protected $_created_column = [
        'column' => 'created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_updated_column = [
        'column' => 'updated_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_table_columns = [
        'notification_id'       => ['type' => 'int',        'key' => 'PRI'],
        'notifier_user_id'      => ['type' => 'int',        'null' => true],
        'notified_user_id'      => ['type' => 'int',        'null' => true],
        'subject_id'            => ['type' => 'int',        'null' => true],
        'subject_name'          => ['type' => 'string',     'null' => true],
        'event_id'              => ['type' => 'string',     'null' => true],
        'url'                   => ['type' => 'string',     'null' => true],
        'is_archived'           => ['type' => 'int',        'null' => true],
        'extra_data_json'       => ['type' => 'string',     'null' => true],
        'created_at'            => ['type' => 'datetime',   'null' => true],
        'updated_at'            => ['type' => 'datetime',   'null' => true]
    ];

    protected $_belongs_to  = [
        'notifier_user'          => [
            'model'         => 'User',
            'foreign_key'   => 'notifier_user_id'
        ],
        'notified_user'          => [
            'model'         => 'User',
            'foreign_key'   => 'notified_user_id'
        ],
        'event'          => [
            'model'         => 'Event',
            'foreign_key'   => 'event_id'
        ]
    ];
}