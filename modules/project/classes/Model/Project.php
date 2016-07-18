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
    	'project_id'			=> ['type' => 'int', 'key' => 'PRI'],
    	'user_id'			=> ['type' => 'int', 'null' => true],
        'name'				=> ['type' => 'string', 'null' => true],
    	'short_description'		=> ['type' => 'string', 'null' => true],
    	'long_description'		=> ['type' => 'string', 'null' => true],
        'email'				=> ['type' => 'string', 'null' => true],
        'phonenumber'			=> ['type' => 'string', 'null' => true],
    	'is_active'			=> ['type' => 'int', 'null' => true],
    	'is_paid'			=> ['type' => 'int', 'null' => true],
        'search_text'			=> ['type' => 'string', 'null' => true],
    	'expiration_date'		=> ['type' => 'datetime', 'null' => true],
    	'salary_type'			=> ['type' => 'int', 'null' => true],
        'salary_low'			=> ['type' => 'int', 'null' => true],
        'salary_high'			=> ['type' => 'int', 'null' => true],
    	'slug'				=> ['type' => 'string', 'null' => true],
    	'created_at'			=> ['type' => 'datetime', 'null' => true],
    	'updated_at'			=> ['type' => 'datetime', 'null' => true], 
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
        'user' => [
            'model'         => 'User',
            'foreign_key'   => 'user_id'
        ]
    ];
    
    protected $_has_many = [
    	'industries' => [
    		'model'     	=> 'Industry',
    		'through'		=> 'projects_industries',
   			'far_key'		=> 'industry_id',
   			'foreign_key'	=> 'project_id',
   		],
   		'professions' => [
    		'model'     	=> 'Profession',
    		'through'		=> 'projects_professions',
    		'far_key'		=> 'profession_id',
   			'foreign_key'	=> 'project_id',
   		],
    	'skills' => [
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
    
    public function short_description()
    {
    	return (strlen($this->short_description) > 100) ? mb_substr($this->short_description, 0, 100) . '...' : $this->short_description;
    }
    
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
        
        if (!$id)
        {        	
        	unset($post['project_id']);
            $post['expiration_date'] = date('Y-m-d', strtotime('+1 month'));
        }                               

        $user = Auth::instance()->get_user();
        $post['user_id'] = $user->user_id;        

        $post['is_paid'] = 1;
        $post['is_active'] = 1;
                
        $submit = parent::submit($post);
        
        if (Arr::get($submit, 'error'))
        {
        	throw new Exception(Arr::get($submit, 'messages'));
        }
        
        // Egyedi slug keszitese
        $this->saveSlug();
        
        // Kapcsolatok mentese
        $this->addRelations($post);                      
        
        $this->search_text = $this->getSearchText();
        $this->save();
        
        $project = new Model_Project($this->project_id);
        
        // Cache
        $project->cacheToCollection();
        
        if (!$id)
        {
            // Ertesitesek letrehozasa
            $user = new Model_User();
            $user->addToProjectNotification($project);                
        }                   

        return $project;
    } 
    
    public function del()
    {
    	$this->is_active = 0;
    	$this->save();
    	
        // Torles cache -bol
    	AB::delete($this->_table_name, $this->pk())->execute();    	        
        
        // Projekt ertesitok torlese
        DB::delete('projects_notifications')->where('project_id', '=', $this->pk())->execute();
    	
    	return ['error' => false];
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
     * Visszaadja a menteni kivant projekthez a searchText -et
     * A searchText a projekt adatibol osszefuzott egyetlen ertek, amiben a felhasznalok keresni tudnak
     * 
     * @param array $data   POST adatok
     * @return string       searchText
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
    
    	foreach ($items as $i => $item)
    	{
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
    	$projects = $this->getAll();
    	
    	return AB::select()->from($projects)->where('is_active', '=', 1)->execute()->count();    	
    }
    
    /**
     * Visszaadja az osszes projekt kapcsolatot (iparagak, szakteruletek, kepessegek), es eltarolja cache -ben
     * Ha az adott kapcsolat megtalalhato cache -ben, akkor onnan adja vissza, ha nem, akkor lekerdezi db -bol
     * 
     * @return array	Kapcsolatok
     */
    public function getAndCacheRelations()
    {
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
    
    /**
     * Visszaadja a az aktiv projekteket datum szerint novekvo sorrendben
     * Cache -bol dolgozik
     * 
     * @return array 	Projektek ORM
     */
    public function getOrdered($limit, $offset)
    {
    	// Csak az aktiv projektek
    	$projects = $this->getAll();    	
    	
    	return AB::select()->from($projects)->where('is_active', '=', 1)->order_by('created_at', 'DESC')->limit($limit)->offset($offset)->execute()->as_array();        	
    }   
    
    /**
     * Projekt kereses. POST -tol fuggoen reszletes, vagy egyszeru
     * 
     * @param array $post	_POST adatok
     * @return array
     */
    public function search(array $post)
    {
    	// Reszletes kereses
    	if (Arr::get($post, 'complex'))
    	{
    		$projects = $this->searchComplex($post);    		
    	}
    	else 	// Egyszeru kereses
    	{
    		$projects = $this->searchSimple($post);
    	}
    	
    	usort($projects, ['Model_Project', 'sortByDate']);
    	
   		return $projects;
    }
    
    /**
     * Egyszeru projekt kereso. Kifejezes alapjan
     * 
     * @param array $post	_POST adatok
     * @return array 		Projektek
     */
    protected function searchSimple(array $post)
    {
    	$projectModel	= new Model_Project();    	
    	$projects		= $projectModel->getAll();    	    	    	
    	$searchTerm 	= Arr::get($post, 'search_term');
    	
    	/**
    	 * @var $projects Array_Builder
    	 */    	
    	$projects = AB::select()->from($projects)->where('is_active', '=', 1)->order_by('created_at', 'DESC');    		    	
    	if (!$searchTerm)
    	{
    		return $projects->execute()->as_array();
    	}
    	
    	return $projects->and_where('search_text', 'LIKE', $searchTerm)->execute()->as_array();    	    
    }
    
    /**
     * Reszletes projekt kereso
     *  - Iparagak
     *  - Szakteruletek
     *  - Kepessegek
     *  - Kepesseg kapcsolat
     *  
     * @param array $post	_POST adatok
     * @return array		Projektek
     */
    protected function searchComplex(array $post)
    {
    	$project 	= new Model_Project();
        $projectsAll    = $project->getAll();
    	$projectsActive	= AB::select()->from($projectsAll)->where('is_active', '=', 1)->order_by('created_at', 'DESC')->execute()->as_array();
                    	 
        // Szukites iparagakra
        $projectsIndustries     = $this->searchByRelation(
            $projectsActive, 
            Arr::get($post, 'industries', []), 
            new Model_Project_Industry()
        );
        
        // Szukites szakteruletekre
        $projectsProfessions    = $this->searchByRelation(
            $projectsIndustries, 
            Arr::get($post, 'professions', []), 
            new Model_Project_Profession()
        );
        
        // Szukites kepessegekre
    	$projectsSkills         = $this->searchBySkills(
            $projectsProfessions, 
            Arr::get($post, 'skills', []), 
            Arr::get($post, 'skill_relation', 1)
        );
    	
    	return $projectsSkills;
    }        
    
    /**
     * Projekt keresese adott tipusu kapcsolat alapjan. Ez lehet Profession, vagy Industry
     * 
     * @param array $projects           Projektek
     * @param array $postRelations      POST kapcsolatok azonositoi
     * @param ORM $relationModel     Keresett kapcsolat ures modelje
     * 
     * @return array                    Talalati lista
     */
    protected function searchByRelation(array $projects, array $postRelations, ORM $relationModel)
    {
        $result = [];
        
        // Nincs keresendo adat
        if (empty($postRelations))
    	{
            return $projects;
    	}
        
    	$cacheRelations     = $relationModel->getAll();                      
        
        foreach ($projects as $project)
    	{
            /**
             * @var $project Model_Project
             */

            // Projekt szakteruletei
            $projectRelations = Arr::get($cacheRelations, $project->project_id, []);

            // Vegmegy a postban kapott, keresett kapcsolatokon. Ha barmelyik egyezik, beleteszi $result -ba a projektet
            foreach ($postRelations as $postRelationId)
            {
                // Ha a postbol kapott kapcsolat megtalalhato a projekt kapcsolatai kozott
                if (in_array($postRelationId, $projectRelations))
                {
                    $result[] = $project;
                    break;
                }
            }
    	}
        
        return $result;            	
    }    
    
    /**
     * A kapott projekteket szukiti a kapott kepessegek alapjan.
     * skill_relation -tol fugg, hogy VAGY / ES kapcsolat van a keresett kepessegek kozott
     *
     * @param array $projects			Projektek (alapesetben a szakteruletekre szukitett projektek)
     * @param array $postSkills			POST kepessegek
     * @param int 	$skillRelation		Keresett kepessegek kapcsolata (ES / VAGY)
     * 
     * @return array
     */
    protected function searchBySkills(array $projects, array $postSkills, $skillRelation = 1)
    {
    	if (empty($postSkills))
    	{
            return $projects;
    	}    	
    	
    	$result = [];
        $projectSkill = new Model_Project_Skill();
        
    	foreach ($projects as $project)
    	{
            /**
             * @var $project Model_Project
             */

            switch ($skillRelation)
            {
                case 1: default:
                        $search = $this->searchBySkillsOr($project, $postSkills, $projectSkill);    				    				
                        break;

                case 2:
                        $search = $this->searchBySkillsAnd($project, $postSkills, $projectSkill);    				    				
                        break;
            }    		

            if ($search)
            {
                $result[] = $project;
            }
    	}
    
    	return $result;
    }
    
    /**
     * Visszaadja, hogy a kapott projektben megtalalhato -e, a kapott kepessegek BARMELYIKE (vagy kapcsolat)
     * 
     * @param Model_Project $project		Projekt
     * @param array     $postSkills		Keresett kepessegek
     * @param Model_Project_Skill               Ures model
     * 
     * @return bool
     */
    protected function searchBySkillsOr(Model_Project $project, array $postSkills, Model_Project_Skill $projectSkill)
    {    	
    	$cacheProjectsSkills		= $projectSkill->getAll();    	
    	
    	// Projekt kepessegei
    	$projectSkills		 	= Arr::get($cacheProjectsSkills, $project->project_id, []);
    	$has					= false;           
    	
    	// Ha nincs a projekthez kepesseg
    	if (empty($projectSkills))
    	{
    		return true;
    	}
    	 
    	// Vegmegy a postban kapott, keresett kepessegeken. Ha barmelyik egyezik, true -e ad vissza
    	foreach ($postSkills as $postSkillId)
    	{    		 
    		// Ha a postbol kapott kepsseg megtalalhato a projekt kepessegei kozott
    		if (in_array($postSkillId, $projectSkills))
    		{
    			$has = true;
    			break;
    		}
    	}
    	
    	return $has;
    }
    
    /**
     * Visszaadja, hogy a kapott projektben megtalalhato -e, a kapott kepessegek MINDEGYIKE (es kapcsolat)
     *
     * @param Model_Project $project		Projekt
     * @param array     $postSkills		Keresett kepessegek
     * @param Model_Project_Skill               Ures model
     *
     * @return bool
     */
    protected function searchBySkillsAnd(Model_Project $project, array $postSkills, Model_Project_Skill $projectSkill)
    {
    	$cacheProjectsSkills		= $projectSkill->getAll();
    	 
    	// Projekt kepessegei
    	$projectSkills		 	= Arr::get($cacheProjectsSkills, $project->project_id, []);
    	$has					= true;
    	
    	// Ha nincs a projekthez kepesseg
    	if (empty($projectSkills))
    	{
    		return true;
    	}
    
    	// Vegmegy a postban kapott, keresett kepessegeken. Ha barmelyik NEM egyezik, false -t ad vissza
    	foreach ($postSkills as $postSkillId)
    	{    		 
    		// Ha a postbol kapott kepesseg NEM talalhato a projekt kepessegei kozott
    		if (!in_array($postSkillId, $projectSkills))
    		{
    			$has = false;
    		}
    	}
    	 
    	return $has;
    }
}
