<?php

/**
 * class Model_Searchlog
 *
 * search_logs tabla ORM osztalya
 * Ez az osztaly tarol minden keresest
 *
 * @author Joó Martin <m4rt1n.j00@gmail.com>
 * @link https://docs.google.com/document/d/1vp-eK9MmS44o1XARQYg9z6nqWl1FhyErFHTObJ_Pyg8/edit#
 * @date 2016.03.23
 */

class Model_Searchlog extends ORM
{
    protected $_table_name = 'search_logs';
    protected $_primary_key = 'search_log_id';

    protected $_created_column = [
        'column' => 'sl_created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_load_with = ['user', 'industry', 'profession', 'skill'];
    
    protected $_table_columns = [
    	'search_log_id'			=> ['type' => 'int', 'key' => 'PRI'],
    	'industry_id'			=> ['type' => 'int', 'null' => true],
   		'profession_id'			=> ['type' => 'int', 'null' => true],
   		'skill_id'				=> ['type' => 'int', 'null' => true],
    	'user_id'				=> ['type' => 'int', 'null' => true],
    	'search_term'			=> ['type' => 'string', 'null' => true],
    	'created_at'			=> ['type' => 'datetime', 'null' => true],
    ];
    
    protected $_belongs_to = [
   		'user' => [
    		'model'         => 'User',
   			'foreign_key'   => 'user_id'
    	],
    	'industry' => [
   			'model'         => 'Industry',
   			'foreign_key'   => 'industry_id'
   		],
    	'profession' => [
    		'model'         => 'Profession',
   			'foreign_key'   => 'profession_id'
   		],
    	'skill' => [
   			'model'         => 'Skill',
   			'foreign_key'   => 'skill_id'
    	],
    ];
    
    public function log(array $post)
    {
    	$complex = Arr::get($post, 'complex');	
    	if ($complex)
    	{
    		
    	}
    	else 
    	{
    		$this->logSimple($post);
    	}
    }
    
    protected function logSimple(array $post)
    {
    	$this->user_id = Auth::instance()->get_user()['user_id'];
    	$this->search_term = Arr::get($post, 'search_term');
    	
    	$this->save();
    }
}
