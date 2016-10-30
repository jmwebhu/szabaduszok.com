<?php

class Model_Project_Partner extends ORM
{
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
        'created_at'            => ['type' => 'datetime',   'null' => true],
        'updated_at'            => ['type' => 'datetime',   'null' => true],
        'accepted_at'           => ['type' => 'datetime',   'null' => true]
    ];

    protected $_belongs_to  = [
        'user'          => [
            'model'         => 'User',
            'foreign_key'   => 'user_id'
        ],
        'project'          => [
            'model'         => 'Project',
            'foreign_key'   => 'project_id'
        ]
    ];
}