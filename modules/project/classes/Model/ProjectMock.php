<?php

/**
 * class Model_Project
 *
 * projects tabla ORM osztalya
 * Ez az osztaly felelos a projektert
 *
 * @author  Joó Martin <m4rt1n.j00@gmail.com>
 * @link    https://docs.google.com/document/d/1vp-eK9MmS44o1XARQYg9z6nqWl1FhyErFHTObJ_Pyg8/edit#
 * @date    2016.03.16
 * @since   1.0
 * @package Core
 */

class Model_ProjectMock extends ORM
{	
	protected $_created_column = [
		'column' => 'created_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_updated_column = [
		'column' => 'updated_at',
		'format' => 'Y-m-d H:i'
	];
    
    protected $_table_columns = [
    	'project_id'        => ['type' => 'int', 'key' => 'PRI'],
    	'user_id'           => ['type' => 'int', 'null' => true],
        'name'              => ['type' => 'string', 'null' => true],
    	'short_description' => ['type' => 'string', 'null' => true],
    	'long_description'  => ['type' => 'string', 'null' => true],
        'email'             => ['type' => 'string', 'null' => true],
        'phonenumber'       => ['type' => 'string', 'null' => true],
    	'is_active'         => ['type' => 'int', 'null' => true],
    	'is_paid'           => ['type' => 'int', 'null' => true],
        'search_text'       => ['type' => 'string', 'null' => true],
    	'expiration_date'   => ['type' => 'datetime', 'null' => true],
    	'salary_type'       => ['type' => 'int', 'null' => true],
        'salary_low'        => ['type' => 'int', 'null' => true],
        'salary_high'       => ['type' => 'int', 'null' => true],
    	'slug'              => ['type' => 'string', 'null' => true],
    	'created_at'        => ['type' => 'datetime', 'null' => true],
    	'updated_at'        => ['type' => 'datetime', 'null' => true],
    ];

    /**
     * @var string $_table_name ORM -hez tartozo tabla neve
     */
    protected $_table_name = 'projects';

    /**
     * @var string $_primary_key Elsodleges kulcs a tablaban
     */
    protected $_primary_key = 'project_id';

    /**
     * @var array $_belongs_to N - 1 es 1 - 1 kapcsolatokat definialja
     */
    protected $_belongs_to = [
        'user'          => [
            'model'         => 'User',
            'foreign_key'   => 'user_id'
        ]
    ];
    
    protected $_has_many = [
    	'industries'    => [
    		'model'     	=> 'Industry',
    		'through'		=> 'projects_industries',
   			'far_key'		=> 'industry_id',
   			'foreign_key'	=> 'project_id',
   		],
   		'professions'   => [
    		'model'     	=> 'Profession',
    		'through'		=> 'projects_professions',
    		'far_key'		=> 'profession_id',
   			'foreign_key'	=> 'project_id',
   		],
    	'skills'        => [
    		'model'     	=> 'Skill',
   			'through'		=> 'projects_skills',
   			'far_key'		=> 'skill_id',
   			'foreign_key'	=> 'project_id',
    	], 
        'notifications' => [
            'model'         => 'Project_Notification',
            'foreign_key'   => 'project_id',
        ]      	
    ];

    public function getRelationString($name)
    {
    	return $name;
    }
}
