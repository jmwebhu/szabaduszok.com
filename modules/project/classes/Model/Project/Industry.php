<?php

class Model_Project_Industry extends Model_Project_Relation
{
    protected $_table_name  = 'projects_industries';
    protected $_primary_key = 'id';

    protected $_belongs_to = [
        'project' => [
            'model'         => 'Project',
            'foreign_key'   => 'project_id'
        ],
        'industry' => [
            'model'         => 'Industry',
            'foreign_key'   => 'industry_id'
        ],
    ];

    protected $_table_columns = [
        'id'            => ['type' => 'int', 'key' => 'PRI'],
        'project_id'	=> ['type' => 'int', 'null' => true],
        'industry_id'	=> ['type' => 'int', 'null' => true],
    ];
}