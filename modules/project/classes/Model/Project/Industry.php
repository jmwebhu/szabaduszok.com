<?php

class Model_Project_Industry extends ORM
{
    protected $_table_name  = 'projects_industries';

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
        'project_id'	=> ['type' => 'int', 'null' => true],
        'industry_id'	=> ['type' => 'int', 'null' => true],
    ];
}