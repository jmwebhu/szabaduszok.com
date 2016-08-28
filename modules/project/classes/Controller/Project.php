<?php

class Controller_Project extends Controller_DefaultTemplate
{
    /**
     * Projekt inditasa
     * 'Uj projektet inditok' gomb megnyomasakor jelenik meg az oldal
     */
    function action_create()
    {
    	try 
		{					
    		$authorization = new Authorization_Project();
    		
    		if (!$authorization->canCreate())
    		{
    			throw new HTTP_Exception_403('Nincs jogosultságod új projektet indítani');
    		}
			
			$this->context->user = $user = Auth::instance()->get_user();					
			$this->context->pageTitle = $this->context->title = Viewhelper_Project::getPageTitle();
			$this->context->hasIdInput = Viewhelper_Project::hasIdInput();
			$this->context->formAction = Viewhelper_Project::getFormAction();
			$this->context->email = Viewhelper_Project::getEmail($user);
			$this->context->phonenumber = Viewhelper_Project::getPhonenumber($user);						
			
			// Iparagak, szakteruletek, kepessegek
			$industry = new Model_Industry();
			
			$this->context->industries = $industry->getAll();	
			
			// Vannak POST adatok, tehat mentes tortenik
			if ($this->request->method() == Request::POST)
			{
				// POST adatok
				$post = Input::post_all();
				
				Model_Database::trans_start();
				
				$project = new Model_Project();
				$project = $project->submit($post);
				
				$result = ['error' => false];																
			}
		}	
		catch (HTTP_Exception_403 $exforbidden)		// Forbidden, nincs jogosultsag
		{
			$exforbidden->setRedirectRoute($this->request->route());
			$exforbidden->setRedirectSlug($this->request->param('slug'));
				
			Session::instance()->set('error', $exforbidden->getMessage());
			$this->defaultExceptionRedirect($exforbidden);						
		}
		catch (Exception $ex)		// Altalanos hiba
		{	
			$this->context->error = __('defaultErrorMessage');
			
			// Logbejegyzest keszit
			$errorLog = new Model_Errorlog();
			$errorLog->log($ex);		
			
			$result = ['error' => true];
		}
		finally 
		{
			if ($this->request->method() == Request::POST)
			{
				Model_Database::trans_end([!Arr::get($result, 'error')]);
					
				// Sikeres regisztracio eseten
				if (!Arr::get($result, 'error'))
				{
					// Atiranyitas kezdooldalra
					header('Location: ' . Route::url('projectProfile', ['slug' => $project->slug]), true, 302);
					die();
				}
			}								
		} 	    	
    }

    /**
     * Projekt modositasa
     * 'Modositom' gomb megnyomasakor jelenik meg az oldal
     */
    function action_update()
    {
    	try 
		{
			$result = ['error' => false];
			
			$slug = $this->request->param('slug');
				
			if (!$slug)
			{
				throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen projekt');
			}
			
			$project = new Model_Project();
			$project = $project->getByColumn('slug', $slug);
			
			if (!$project->loaded() || !$project->is_active)
			{
				throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen projekt');
			}
			
			$authorization = new Authorization_Project($project);
			
			if (!$authorization->canEdit())
			{
				throw new HTTP_Exception_403('Nincs jogosultságod szerkeszteni a projektet');
			}
			
			// Iparagak, szakteruletek, kepessegek
			$industry = new Model_Industry();
			$profession = new Model_Profession();
			$skill = new Model_Skill();
			
			$user = Auth::instance()->get_user();
			
			$this->context->industries = $industry->getAll();
			$this->context->professions = $profession->getAll();
			$this->context->skills = $skill->getAll();
			
			$this->context->pageTitle = Viewhelper_Project::getPageTitle('edit', $project) . $project->name;
			$this->context->title = 'Szabadúszó projekt szerkesztése ' . $project->name;	
			$this->context->project = $project;
			
			$this->context->email = Viewhelper_Project::getEmail($user, 'edit', $project);
			$this->context->phonenumber = Viewhelper_Project::getPhonenumber($user, 'edit', $project);
			
			$this->context->formAction = Viewhelper_Project::getFormAction('edit', $project);			
			$this->context->hasCancel = (int) $authorization->hasCancel();			
			$this->context->user = $user;					
			
			// Vannak POST adatok, tehat mentes tortenik
			if ($this->request->method() == Request::POST)
			{
				// POST adatok
				$post = Input::post_all();
			
				Model_Database::trans_start();
				
				$project = new Model_Project();
				$project = $project->submit($post);
			
				$result = ['error' => false];
			}
		}
		catch (HTTP_Exception_404 $exnf)	// 404 Nof found
		{
			Session::instance()->set('error', $exnf->getMessage());
			$this->defaultExceptionRedirect($exnf);
		}
		catch (HTTP_Exception_403 $exforbidden)		// Forbidden, nincs jogosultsag
		{						
			$exforbidden->setRedirectRoute($this->request->route());
			$exforbidden->setRedirectSlug($this->request->param('slug'));
			
			Session::instance()->set('error', $exforbidden->getMessage());
			$this->defaultExceptionRedirect($exforbidden);
		}	
		catch (Exception $ex)
		{
			$this->context->error = __('defaultErrorMessage');
			$errorLog = new Model_Errorlog();
			$errorLog->log($ex);
			
	    	$result = ['error' 	=> true];	
		}
		finally
		{
			if ($this->request->method() == Request::POST)
			{				
				Model_Database::trans_end([!Arr::get($result, 'error')]);								
				
				// Sikeres regisztracio eseten
				if (!Arr::get($result, 'error'))
				{
					header('Location: ' . Route::url('projectProfile', ['slug' => $project->slug]), true, 302);
					die();
				}
			}		    
		}
    }

    function action_profile()
    {    	
    	try
    	{    	    		
    		$result = ['error' => false];
    		$slug = $this->request->param('slug');
    			
    		if (!$slug)
    		{
    			throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen projekt');
    		}
    			    		    		
    		$project	= new Model_Project();
    		$user 		= new Model_User();
    		
    		/**
    		 * @var $project Model_Project
    		 */
    		$project = $project->getByColumn('slug', $slug);
    		$project->with('user');
    			
    		if (!$project->loaded() || !$project->is_active)
    		{
    			throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen projekt');
    		}
    		
    		$this->context->user = $user->getById($project->user_id);
    		
    		$logged 					= Auth::instance()->get_user();
    		$myRating					= $logged->getMyRating($this->context->user);
    		$this->context->myRating	= ($myRating) ? $myRating : '-';
    		
    		$authorization		= new Authorization_Project($project);
    		$authorizationUser	= new Authorization_User($this->context->user);
    		
    		$this->context->project = $project;    		    		
    		$this->context->title = 'Szabadúszó projekt ' . $project->name;    			    		
    			    		    		    			
    		$this->context->canRate = (int) $authorizationUser->canRate();
    		$this->context->canEdit = (int) $authorization->canEdit();    		
    		$this->context->canDelete = (int) $authorization->canDelete();
    		$this->context->salary = Viewhelper_Project::getSalary($project);
    		
    		$relations = $project->getAndCacheRelations();    
    		
    		$this->context->industries = Arr::get($relations, 'industries');
    		$this->context->professions = Arr::get($relations, 'professions');
    		$this->context->skills = Arr::get($relations, 'skills');
    		
    	}
    	catch (HTTP_Exception_404 $exnf)	// 404 Nof found
		{
			Session::instance()->set('error', $exnf->getMessage());
			$this->defaultExceptionRedirect($exnf);
		}
    	catch (Exception $ex)		// Altalanos hiba
    	{
    		$this->context->error = __('defaultErrorMessage');
    	
    		// Logbejegyzest keszit
    		$errorLog = new Model_Errorlog();
    		$errorLog->log($ex);
    	
    		$result = ['error' => true];
    	}
    }

    /**
     * Projektek lista
     * Kezdoldal
     */
    function action_list()
    {    	    	               
        $project		= new Model_Project();
        
        $limit 			= Kohana::$config->load('projects')->get('pagerLimit');
        $currentPage 	= $this->request->param('page') ? $this->request->param('page') : 1;
        $offset			= (($currentPage - 1) * $limit);        
        
        if ($this->request->method() == Request::POST)
        {        	
        	$this->context->needPager = false;
        	
        	if (Input::post('complex'))
        	{
        		$postProfessionIds = Input::post('professions', []);
        		$postSkillIds = Input::post('skills', []);
        		 
        		$postProfessions	= [];
        		$postSkills 		= [];
        		 
        		$profession	= new Model_Profession();
        		$skill 		= new Model_Skill();
        		 
        		foreach ($postProfessionIds as $id)
        		{
        			$postProfessions[] = $profession->getById($id);
        		}
        		 
        		foreach ($postSkillIds as $id)
        		{
        			$postSkills[] = $skill->getById($id);
        		}
        		 
        		$this->context->postIndustries 		= Input::post('industries');
        		$this->context->postProfessions 	= $postProfessions;
        		$this->context->postSkills 			= $postSkills;
        		$this->context->postSkillRelation	= Input::post('skill_relation', 1);
        		$this->context->current				= 'complex';        		       
        	}
        	else 
        	{
        		$this->context->searchTerm			= Input::post('search_term');
        		$this->context->current				= 'simple';
        	}        	
        	
        	$projects = $project->search(Input::post_all());
        }    
        else
        {        	        	
        	$this->context->needPager 	= true;
        	$projects					= $project->getOrdered($limit, $offset);        	
        }                
                    
        $user		= new Model_User();
        $industry	= new Model_Industry();
        
        $relations		= [];
        $salaries		= [];
        $users 			= [];
        $cacheUsers		= $user->getAll();
        
        $countProjects	 	= count($projects);
        $countAllProjects 	= $project->getCount();
         
        foreach ($projects as $pr)
        {
        	$relations[$pr->project_id]	= $pr->getAndCacheRelations();
        	$salaries[$pr->project_id]	= Viewhelper_Project::getSalary($pr);
        	$users[$pr->project_id]		= Arr::get($cacheUsers, $pr->user_id);
        }
                 
        $this->context->relations	= $relations;
        $this->context->salaries	= $salaries;
        $this->context->users		= $users;
        $this->context->industries	= $industry->getAll();
        $this->context->title 		= 'Szabadúszó projektek, munkák';                
                
        $this->context->projects	= $projects;
        
        /**
         * @var $pagesFloat				Lapok szama tizedes szamban. Projektek szama / limit. Pl.: 33 / 10 = 3.3 
         * @var $pagesCount				Lapok szama egesz szamban. Pl. 3.3 => 3
         * @var $pagesDecimalRemainder	A ket ertek kulonbsege, tehat a tizedes maradek. Pl.: 3.3 - 3 = 0.3
         * 
         * Ha van maradek, akkor egyel tobb lap van
         */             
        
        $pagesFloat 			= $countAllProjects / $limit;		// Pl.: 33 / 10 = 3.3
        $pagesCount 			= floor($pagesFloat);				// 3.3 => 3
        $pagesDecimalRemainder	= $pagesFloat - $pagesCount;		// 3.3 - 3 = 0.3
        
        // Ha van tizedes maradek, akkor egyel tobb lap kell
        if ($pagesDecimalRemainder != 0)
        {
        	$pagesCount++;
        }
        
        $pages = [];
        for ($i = 1; $i <= $pagesCount; $i++)
        {
        	$pages[] = $i;
        }
        
        $this->context->pagesCount			= $pagesCount;
        $this->context->pages				= $pages;
        $this->context->currentPage			= $currentPage;
        $this->context->countProjects		= $countProjects;
        $this->context->countAllProjects	= $countAllProjects;
        
        $nextPage = $currentPage + 1;
        $prevPage = $currentPage - 1;        
        
        if ($currentPage == $pagesCount)
        {
        	$nextPage = $pagesCount;
        }
        
        if ($currentPage == 1)
        {
        	$prevPage = 1;
        }
        
        $this->context->nextPage = $nextPage;
        $this->context->prevPage = $prevPage;       
    }

    public function action_ajax()
	{       
    	try
    	{
    		Model_Database::trans_start();
    		$result = ['error' => false];
    		
	    	if (!$this->request->is_ajax())
	    	{
	    		throw new HTTP_Exception_400('Only Ajax');
	    	}
	    	
	    	$this->auto_render = false;
			
	        switch ($this->request->param('actiontarget'))
	        {
	        	case 'del':
					$project = new Model_Project(Input::post('id'));
					$json = json_encode($project->del());

					break;
	                
	            case 'professionAutocomplete':
	            	$profession = new Model_Profession();              	
	            	$json = json_encode($profession->relationAutocomplete(Input::get('term')));
	            	            	
	            	break;
	            	
	            case 'skillAutocomplete':
					$skill = new Model_Skill();
					$json = json_encode($skill->relationAutocomplete(Input::get('term')));

					break;
	                
	            default:
	            	throw new HTTP_Exception_400('Action target not found');
			}
		}
		catch (Exception $ex)		// Altalanos hiba
		{
			// Logbejegyzest keszit
			$errorLog = new Model_Errorlog();
			$errorLog->log($ex);
		
			$result = ['error' => true];
			$json = json_encode($result);
			$this->response->status(500);
		}
		finally
		{
			Model_Database::trans_end([!Arr::get($result, 'error')]);
		}
		
		$this->response->body($json);
	}
}
