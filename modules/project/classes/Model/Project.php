<?php

/**
 * class Model_Project
 *
 * Felelosseg: Projekt lekerdezesek
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

    /**
     * @return Array_Builder
     */
    public function baseSelect()
    {
        $base = parent::baseSelect();
        return $base->where('is_active', '=', 1);
    }

    /**
     * @param array $post
     * @return Model_Project
     * @throws Exception
     */
    public function submit(array $post)
    {    	           
        $id = Arr::get($post, 'project_id');

        if (!$id) {
            unset($post['project_id']);
            $post['expiration_date'] = date('Y-m-d', strtotime('+1 month'));
        }

        $post = $this->setDefaultProperties($post);
                
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

    /**
     * @return array ['error']
     */
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

    /**
     * @param string $event
     */
    public function notifyObservers($event)
    {
        switch ($event) {
            case self::EVENT_INACTIVATE:
                $this->notifyObserversByInactivate();
                break;
        }
    }

    /**
     * @param array $post
     * @return array
     */
    protected function setDefaultProperties(array $post)
    {
        $data = $post;

        $user               = Auth::instance()->get_user();
        $data['user_id']    = $user->user_id;
        $data['is_paid']    = 1;
        $data['is_active']  = 1;

        return $data;
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

    /**
     * @param array $post ['industries', 'professions', 'skills']
     */
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
     *
     * @param string $relationName
     * @return string
     */
    public function getRelationString($relationName)
    {
    	$items  = $this->{$relationName}->find_all();
        $sb     = SB::create();

    	foreach ($items as $i => $item) {
            $sb->append($item->name);

            if ($i == count($items) - 1) {
                $sb->append(', ');
            }
    	}
    
    	return $sb->get('');
    }

    /**
     * Visszaadja, hogy hany aktiv projekt van
     * 
     * @return int
     */
    public function getCount()
    {
    	return $this->baseSelect()->execute()->count();
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getOrderedAndLimited($limit, $offset)
    {
        return $this->orderBy('created_at')
            ->limit($limit)->offset($offset)
            ->execute()->as_array();
    }

    /**
     * @param bool $execute
     * @param string $direction
     * @return Array_Builder|array
     */
    public function getOrderedByCreated($execute = true, $direction = 'DESC')
    {
        $builder = $this->orderBy('created_at', $direction);

        if ($execute) {
            return $builder->execute()->as_array();
        }

        return $builder;
    }

    /**
     * @param string $field
     * @param string $direction
     * @return Array_Builder
     */
    protected function orderBy($field, $direction = 'DESC')
    {
        return $this->baseSelect()->order_by($field, $direction);
    }

    /**
     * @return array ['industries', 'professions', 'skills']
     */
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

    /**
     * @param ORM $relationModel
     * @return array
     */
    protected function getRelation(ORM $relationModel)
    {
        $relations          = $relationModel->getAll();
        $projectRelations   = Arr::get($relations, $this->project_id, []);

        return $projectRelations;
    }
}
