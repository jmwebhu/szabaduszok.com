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

class Model_Project extends ORM implements Subject
{
    const EVENT_INACTIVATE = 'inactivate';

    protected $_table_name  = 'projects';
    protected $_primary_key = 'project_id';

	protected $_created_column = [
		'column' => 'created_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_updated_column = [
		'column' => 'updated_at',
		'format' => 'Y-m-d H:i'
	];
    
    protected $_table_columns = [
    	'project_id'        => ['type' => 'int',        'key' => 'PRI'],
    	'user_id'           => ['type' => 'int',        'null' => true],
        'name'              => ['type' => 'string',     'null' => true],
    	'short_description' => ['type' => 'string',     'null' => true],
    	'long_description'  => ['type' => 'string',     'null' => true],
        'email'             => ['type' => 'string',     'null' => true],
        'phonenumber'       => ['type' => 'string',     'null' => true],
    	'is_active'         => ['type' => 'int',        'null' => true],
    	'is_paid'           => ['type' => 'int',        'null' => true],
        'search_text'       => ['type' => 'string',     'null' => true],
    	'expiration_date'   => ['type' => 'datetime',   'null' => true],
    	'salary_type'       => ['type' => 'int',        'null' => true],
        'salary_low'        => ['type' => 'int',        'null' => true],
        'salary_high'       => ['type' => 'int',        'null' => true],
    	'slug'              => ['type' => 'string',     'null' => true],
    	'created_at'        => ['type' => 'datetime',   'null' => true],
    	'updated_at'        => ['type' => 'datetime',   'null' => true]
    ];

    protected $_belongs_to  = [
        'user'          => [
            'model'         => 'User',
            'foreign_key'   => 'user_id'
        ]
    ];
    
    protected $_has_many    = [
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

    public function baseSelect()
    {
        $base = parent::baseSelect();
        return $base->where('is_active', '=', 1);
    }

    public function submit(array $post)
    {    	           
        $id = Arr::get($post, 'project_id');

        if (!$id) {
            unset($post['project_id']);
            $post['expiration_date'] = date('Y-m-d', strtotime('+1 month'));
        }

        $user               = Auth::instance()->get_user();
        $post['user_id']    = $user->user_id;
        $post['is_paid']    = 1;
        $post['is_active']  = 1;
                
        $submit = parent::submit($post);
        
        if (Arr::get($submit, 'error')) {
        	throw new Exception(Arr::get($submit, 'messages'));
        }

        $this->saveSlug();
        $this->addRelations($post);
        
        if (!$id) {
            $user = new Model_User();
            $user->addToProjectNotification($this);
        }

        return $this;
    }
    
    public function inactivate()
    {
        try {
            $error = false;
            Model_Database::trans_start();

            $this->is_active = 0;
            $this->save();

            $this->clearCache();
            $this->notifyObservers(self::EVENT_INACTIVATE);

        } catch (Exception $ex) {
            $error = true;
            Log::instance()->addException($ex);

        } finally {
            Model_Database::trans_end([!$error]);
        }
    	
    	return ['error' => $error];
    }

    public function notifyObservers($event)
    {
        switch ($event) {
            case self::EVENT_INACTIVATE:
                $this->notifyObserversByInactivate();
                break;
        }
    }

    protected function notifyObserversByInactivate()
    {
        foreach ($this->notifications->find_all() as $notification) {
            /**
             * @var $notification Observer
             */
            $notification->notify(self::EVENT_INACTIVATE);
        }
    }

    protected function addRelations(array $post)
    {
    	$this->removeAll('projects_industries', 'project_id');
    	$this->removeAll('projects_professions', 'project_id');
    	$this->removeAll('projects_skills', 'project_id');
    	
        $this->addRelation($post, new Model_Project_Industry(), new Model_Industry());
        $this->addRelation($post, new Model_Project_Profession(), new Model_Profession());
        $this->addRelation($post, new Model_Project_Skill(), new Model_Skill());
    }
    
    /**
     * A projekthez tartozo osszes kapcsolat (iparagak, szakterulat, kepesseg) nevet osszefuzi egy stringbe
     */
    public function getRelationString($relationName)
    {
    	$items = $this->{$relationName}->find_all();
        $sb = SB::create();
    
    	foreach ($items as $i => $item) {
            $sb->append($item->name);

            if ($i == count($items) - 1) {
                $sb->append(', ');
            }
    	}
    
    	return $sb->get();
    }    

    /**
     * Visszaadja, hogy hany aktiv projekt van
     * 
     * @return int $count
     */
    public function getCount()
    {
    	return $this->baseSelect()->execute()->count();
    }

    public function getOrderedAndLimited($limit, $offset)
    {
        return $this->orderBy('created_at')
            ->limit($limit)->offset($offset)
            ->execute()->as_array();
    }

    public function getOrderedByCreated($execute = true, $direction = 'DESC')
    {
        $builder = $this->orderBy('created_at', $direction);

        if ($execute) {
            return $builder->execute()->as_array();
        }

        return $builder;
    }

    protected function orderBy($field, $direction = 'DESC')
    {
        return $this->baseSelect()->order_by($field, $direction);
    }

    public function getRelations()
    {
        if (!$this->loaded()) {
            return [];
        }

    	return [
    		'industries'	=> $this->getRelation(new Model_Project_Industry()),
    		'professions'	=> $this->getRelation(new Model_Project_Profession()),
    		'skills'		=> $this->getRelation(new Model_Project_Skill())
    	];
    }

    protected function getRelation(ORM $relationModel)
    {
        $relations          = $relationModel->getAll();
        $projectRelations   = Arr::get($relations, $this->project_id, []);

        return $projectRelations;
    }
}
