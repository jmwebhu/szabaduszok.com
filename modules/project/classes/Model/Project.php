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

class Model_Project extends ORM
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

    protected $_table_name  = 'projects';
    protected $_primary_key = 'project_id';

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

    /**
     * @todo Business_Project::getShortDescriptionCutOffAt() csere
     */
    public function short_description()
    {
    	return (strlen($this->short_description) > 100) ? mb_substr($this->short_description, 0, 100) . '...' : $this->short_description;
    }

    /**
     * @todo Business_Project::getNameCutOffAt() csere
     */
    public function name()
    {
    	return (strlen($this->name) > 70) ? mb_substr($this->name, 0, 70) . '...' : $this->name;
    }

    /**
     * Elmenti a projektet a kapott POST adatok alapjan
     * 
     * @param array $post   POST adatok
     * @return array        Eredmeny
     */
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
        
        $this->search_text = $this->getSearchText();
        $this->save();

        $this->cacheToCollection();
        
        if (!$id) {
            $user = new Model_User();
            $user->addToProjectNotification($this);
        }

        return $this;
    }
    
    public function del()
    {
        try {
            $error = false;
            Model_Database::trans_start();

            $this->inactivate();

        } catch (Exception $ex) {
            $error = true;
            Log::instance()->addException($ex);

        } finally {
            Model_Database::trans_end([!$error]);
        }
    	
    	return ['error' => $error];
    }

    protected function inactivate()
    {
        $this->is_active = 0;
        $this->save();

        $this->clearCache();
        Model_Project_Notification::deleteAllByProject($this);
    }

    protected function clearCache()
    {
        AB::delete($this->_table_name, $this->pk())->execute();
    }
    
    /**
     * Hozzaadja a projekthez az osszes kapcsolatot
     * 
     * @param array $post   _POST adatok
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
     * @todo Business_Project::getSearchText() csere
     */
    public function getSearchText()
    {        
        $searchText = $this->name . ' ' . $this->short_description . ' ' . $this->long_description . ' ' . $this->email . ' ' . $this->phonenumber .  ' ' . date('Y-m-d') . ' ';               
        if ($this->user->loaded())
        {        
            $searchText .= $this->user->name() . ' ' . $this->user->address_city . ' ';
            
            if ($this->user->is_company)
            {
            	$searchText .= $this->user->company_name . ' ';
            }            
        }
        
        $relations = ['industries', 'professions', 'skills'];
        
        foreach ($relations as $relation)
        {
        	$text = $this->getRelationString($relation);
        	$searchText .= $text . ' ';
        }        

        return $searchText;
    }
    
    /**
     * Visszaadja a kapott kapcsolatokbol alkotott stringet.
     * A projekthez tartozo osszes entitas (iparagak, stb) nevet osszefuzi egy stringbe
     *
     * @param string $name		Kapcsolat neve
     * @return string			Eredmeny
     */
    public function getRelationString($name)
    {
    	$items = $this->{$name}->find_all();
    	$result = '';
    
    	foreach ($items as $i => $item) {
    		$result .= ($i == count($items) - 1) ? $item->name : ($item->name . ', ');
    	}
    
    	return $result;
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
    
    /**
     * Visszaadja az osszes projekt kapcsolatot (iparagak, szakteruletek, kepessegek), es eltarolja cache -ben
     * Ha az adott kapcsolat megtalalhato cache -ben, akkor onnan adja vissza, ha nem, akkor lekerdezi db -bol
     * 
     * @return array	Kapcsolatok
     */
    public function getAndCacheRelations()
    {
        if (!$this->loaded()) {
            return false;
        }

    	$cache 				= Cache::instance();
    	
    	$projectIndustry 	= new Model_Project_Industry();
    	$projectProfession	= new Model_Project_Profession();
    	$projectSkill 		= new Model_Project_Skill();
    	
    	// Kapcsolatok kiszedese cache -bol.
    	$industries 		= $projectIndustry->getAll();    	
    	$professions		= $projectProfession->getAll();    	
    	$skills 			= $projectSkill->getAll();

    	// Nincsenek iparagak cache -ben a projekthez
    	if (!isset($industries[$this->project_id]))
    	{
    		// Lekerdezi db -bol es osszegyujti a cache -nek megfelelo formatumban
    		$industriesAll 		= $this->industries->find_all();
    		$resultIndustries	= [];
    	
    		foreach ($industriesAll as $industry)
    		{
    			$resultIndustries[] = $industry;
    		}

    		$industries[$this->project_id] = $resultIndustries;
    		$cache->set('projects_industries', $industries);
    	}
    	
    	// Nincsenek szakteruletek cache -ben a projekthez
    	if (!isset($professions[$this->project_id]))
    	{
    		$professionsAll = $this->professions->find_all();
    		$resultProfessions = [];
    		
    		// Lekerdezi db -bol es osszegyujti a cache -nek megfelelo formatumban
    		foreach ($professionsAll as $profession)
    		{
    			$resultProfessions[] = $profession;
    		}
    		
    		$professions[$this->project_id] = $resultProfessions;
    		$cache->set('projects_professions', $professions);
    	}
    	
    	// Nincsenek kepessegek cache -ben a projekthez
    	if (!isset($skills[$this->project_id]))
    	{
    		// Lekerdezi db -bol es osszegyujti a cache -nek megfelelo formatumban
    		$skillsAll = $this->skills->find_all();
    		$resultSkills = [];
    		
    		foreach ($skillsAll as $skill)
    		{
    			$resultSkills[] = $skill;
    		}
    		
    		$skills[$this->project_id] = $resultSkills;
    		$cache->set('projects_skills', $skills);
    	}

    	return [
    		'industries'	=> Arr::get($industries, $this->project_id),
    		'professions'	=> Arr::get($professions, $this->project_id),
    		'skills'		=> Arr::get($skills, $this->project_id)
    	];
    }

    protected function orderBy($field, $direction = 'DESC')
    {
        return $this->baseSelect()->order_by($field, $direction);
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
}
