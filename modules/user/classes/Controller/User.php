<?php

class Controller_User extends Controller_DefaultTemplate
{
	public function logout()
	{
		Auth::instance()->logout(true, true);
		
		header('Location: ', Route::url('home'));
		die();
	}
	
	public function action_freelancerregistration()
	{		    
		try 
		{							
			$this->context->pageTitle			= $this->context->title = Viewhelper_User::getPageTitleFreelancer();
			$this->context->hasPrivacyCheckbox	= Viewhelper_User::hasPrivacyCheckbox();
			$this->context->passwordText		= Viewhelper_User::getPasswordText();
			$this->context->hasIdInput			= Viewhelper_User::hasIdInput();
			$this->context->formAction			= Viewhelper_User::getFormActionFreelancer();
			$this->context->hasPasswordRules	= Viewhelper_User::hasPasswordRules();			
			$profile							= new Model_Profile();
			$this->context->profiles			= $profile->where('is_active', '=', 1)->find_all();
                        
			$email  = Input::get('email');
			$signup = new Model_Signup();
			$signup->createIfHasEmail($email);

			$leadMagnet = new Model_Leadmagnet();
			Model_User::sendLeadMagnet($email, $leadMagnet);

			$this->context->email           = $email;
			$this->context->landingPageName = Input::get('landing_page_name');
			
			// Iparagak
			$industry = new Model_Industry();
			$this->context->industries = $industry->getAll();
			
			// Vannak POST adatok, tehat mentes tortenik
			if ($this->request->method() == Request::POST)
			{
				// POST adatok
				$post = Input::post_all();
				
				Model_Database::trans_start();
				
				//$user = new Model_User();
				//$user->registerFreelancer($post);
                $user = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
                $user->submit($post);
				
				$result = ['error' => false];
				
				// Bejelentkeztetes
				Auth_ORM::instance()->force_login($user->getModel());
			}
		}		
		catch (Exception_UserRegistration $exur)		// Regisztracios hiba
		{
			// Visszaadja a view -nak
			$this->context->error = $exur->getMessage();		
			
			$result = ['error' => true];
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
					$id = Arr::get($post, 'user_id');
					$api = Api_Mailservice::instance();

					$user->addToMailService($api, 1, $id);
					
					// Atiranyitas kezdooldalra
					header('Location: ' . Route::url('freelancerProfile', ['slug' => $user->getSlug()]), true, 302);
					die();
				}
			}								
		}
	}
	
	public function action_projectownerregistration()
	{
		try
		{									
			$this->context->hasPrivacyCheckbox = Viewhelper_User::hasPrivacyCheckbox();
			$this->context->pageTitle = $this->context->title = Viewhelper_User::getPageTitleProjectowner();
			$this->context->passwordText = Viewhelper_User::getPasswordText();
			$this->context->hasIdInput = Viewhelper_User::hasIdInput();
			$this->context->formAction = Viewhelper_User::getFormActionProjectowner();
			$this->context->hasPasswordRules = Viewhelper_User::hasPasswordRules();
                        
			$email = Input::get('email');
			$signup = new Model_Signup();
			$signup->createIfHasEmail($email, 2);

			$leadMagnet = new Model_Leadmagnet();
			Model_User::sendLeadMagnet($email, $leadMagnet, 2);

			$this->context->email           = $email;
			$this->context->landingPageName = Input::get('landing_page_name');
			
			// Iparagak
			$industry = new Model_Industry();
			$this->context->industries = $industry->getAll();
			
			// Vannak POST adatok, tehat mentes tortenik
			if ($this->request->method() == Request::POST)
			{
				// POST adatok
				$post = Input::post_all();
				
				Model_Database::trans_start();
	
				//$user = new Model_User();
				//$user->registerProjectowner($post);
                $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
                $user->submit($post);
	
				$result = ['error' => false];
	
				// Bejelentkeztetes
				Auth_ORM::instance()->force_login($user->getModel());
			}
		}
		catch (Exception_UserRegistration $exur)		// Regisztracios hiba
		{
		    echo Debug::vars($exur);
            exit;
			// Visszaadja a view -nak
			$this->context->error = $exur->getMessage();
				
			$result = ['error' => true];
		}
		catch (Exception $ex)		// Altalanos hiba
		{
		    echo Debug::vars($ex);
            exit;
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
					$id = Arr::get($post, 'user_id');
					$api = Api_Mailservice::instance();

					$user->addToMailService($api, 2, $id);
					
					// Atiranyitas kezdooldalra
					header('Location: ' . Route::url('projectOwnerProfile', ['slug' => $user->getSlug()]), true, 302);
					die();
				}
			}
		}
	}
	
	function action_freelancerprofileedit()
	{				    
		try 
		{			
			$result = ['error' => false];
			
			$slug = $this->request->param('slug');
				
			if (!$slug)
			{
				throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');
			}
			
			$user = new Model_User();
			$user = $user->getByColumn('slug', $slug);
			
			if (!$user->loaded())
			{
				throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');
			}
			
			$authorization = new Authorization_User($user);
			
			if (!$authorization->canEdit())
			{
				throw new HTTP_Exception_403('Nincs jogosultságod a profil szerkesztéséhez');
			}
			
			$industry							= new Model_Industry();			
			$this->context->title				= 'Szabadúszó profil szerkesztés';
			$this->context->hasCancel			= (int) $authorization->hasCancel();
			$this->context->user				= $user;
			$this->context->industries			= $industry->getAll();			
			$this->context->hasPrivacyCheckbox	= Viewhelper_User::hasPrivacyCheckbox('edit');
			$this->context->pageTitle			= Viewhelper_User::getPageTitleFreelancer('edit');
			$this->context->passwordText		= Viewhelper_User::getPasswordText('edit');
			$this->context->hasIdInput			= Viewhelper_User::hasIdInput('edit');
			$this->context->formAction			= Viewhelper_User::getFormActionFreelancer('edit', $user);
			$this->context->hasPasswordRules	= Viewhelper_User::hasPasswordRules('edit');
			$this->context->hasPicture			= Viewhelper_User::hasPicture($user);
			$this->context->hasCv = $hasCv		= Viewhelper_User::hasCv($user);		
			
			$profile							= new Model_Profile();
			$this->context->profiles			= $profile->where('is_active', '=', 1)->find_all();
			$this->context->userProfileUrls		= $user->getProfileUrls(new Model_User_Profile());
		
			if ($hasCv)
			{
				$parts = explode('.', $user->cv_path);
				$this->context->cvExt = $parts[count($parts) - 1];
			}
			
			// Mentes
			if ($this->request->method() == Request::POST)
			{
				// POST adatok
				$post = Input::post_all();
				
				Model_Database::trans_start();
				
				//$user = new Model_User();
				//$user = $user->registerFreelancer($post);
                $user = Entity_User::createUser(Entity_User::TYPE_FREELANCER, $user->user_id);
                $user->submit($post);
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
		catch (Exception_UserRegistration $exur)		// Regisztracios hiba
		{
			// Visszaadja a view -nak
			$this->context->error = $exur->getMessage();
		
			$result = ['error' => true];
		}
		catch (Exception $ex)
		{
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
					$id = Arr::get($post, 'user_id');
					$api = Api_Mailservice::instance();

					$user->addToMailService($api, 1, $id);
					
					header('Location: ' . Route::url('freelancerProfile', ['slug' => $user->getSlug()]), true, 302);
					die();
				}
			}		    
		}		    	
	}
	
	function action_projectownerprofileedit()
	{
		try
		{
			$result = ['error' => false];			
			$slug = $this->request->param('slug');
				
			if (!$slug)
			{
				throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');
			}
			
			$user = new Model_User();
			$user = $user->getByColumn('slug', $slug);
			
			if (!$user->loaded())
			{
				throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');
			}
			
			$authorization = new Authorization_User($user);
			
			if (!$authorization->canEdit())
			{
				throw new HTTP_Exception_403('Nincs jogosultságod a profil szerkesztéséhez');
			}
			
			$industry = new Model_Industry();
			
			$this->context->title = 'Megbízó profil szerkesztés';
			$this->context->hasCancel = (int) $authorization->hasCancel();
			$this->context->user = $user;
			$this->context->industries = $industry->getAll();
			$this->context->hasPrivacyCheckbox = Viewhelper_User::hasPrivacyCheckbox('edit');
			$this->context->pageTitle = Viewhelper_User::getPageTitleProjectowner('edit', $user);
			$this->context->passwordText = Viewhelper_User::getPasswordText('edit');
			$this->context->hasIdInput = Viewhelper_User::hasIdInput('edit');
			$this->context->formAction = Viewhelper_User::getFormActionProjectowner('edit', $user);
			$this->context->hasPasswordRules = Viewhelper_User::hasPasswordRules('edit');
			$this->context->hasPicture = Viewhelper_User::hasPicture($user);			
			
			$userIndustries = $user->industries->find_all();
			$industryIds = [];
			foreach ($userIndustries as $userIndustry)
			{
				$industryIds[] = $userIndustry->industry_id;
			}
				
			$this->context->industryIds = $industryIds;
				
			// Mentes
			if ($this->request->method() == Request::POST)
			{
				//$user = new Model_User();
				$post = Input::post_all();
				//$user = $user->registerProjectowner($post);
                $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER, $user->user_id);
                $user->submit($post);
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
		catch (Exception_UserRegistration $exur)		// Regisztracios hiba
		{
			// Visszaadja a view -nak
			$this->context->error = $exur->getMessage();
		
			$result = ['error' => true];
		}
		catch (Exception $ex)
		{
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
					$id = Arr::get($post, 'user_id');
					$api = Api_Mailservice::instance();

					$user->addToMailService($api, 1, $id);
					
					header('Location: ' . Route::url('projectOwnerProfile', ['slug' => $user->getSlug()]), true, 302);
					die();
				}
			}
		}
	}
	
	public function action_login()
	{		    
		try 
		{			    		    					
			$this->context->title = 'Belépés';
			$sessionError = Session::instance()->get('error');
			
			if ($sessionError)
			{
				$this->context->error = $sessionError;
				Session::instance()->delete('error');
			}			
			
			$result = ['error' => false];
			
			// Vannak POST adatok, tehat belepes tortenik
			if ($this->request->method() == Request::POST)
			{
				Model_Database::trans_start();
				
				// POST adatok
				$post = Input::post_all();		
				
				$user = new Model_User();
				$url = $user->login($post);								
			}			
		}
		catch (Exception_UserLogin $exul)		// Belepesi hiba
		{
			// Visszaadja a view -nak
			$this->context->error = $exul->getMessage();
		
			$result = ['error' => true];
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
					$redirectUrl = Session::instance()->get('redirect_url'); 
					if ($redirectUrl)
					{
						Session::instance()->delete('redirect_url');
						$url = $redirectUrl;
					}
					
					// Atiranyitas a megfelelo url -re
					header('Location: ' . $url, true, 302);
					die();
				}
			}
		}						
	}
	
	public function action_passwordreminder()
	{			
		try
		{
			$this->context->title = 'Elfelejtett jelszó';
			$result = ['error' => false];
				
			// POST keres
			if ($this->request->method() == Request::POST)
			{
				Model_Database::trans_start();
		
				// POST adatok
				$post = Input::post_all();
				
				$user = new Model_User();
				$user->passwordReminder(Arr::get($post, 'email'));
			}
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
					// Atiranyitas a megfelelo url -re
					header('Location: ' . Route::url('login'), true, 302);
					die();
				}
			}
		}		 
	}
	
	function action_freelancerprofile()
	{
		try
		{			
			if (Session::instance()->get('error'))
			{
				$this->context->session_error = Session::instance()->get('error');
				Session::instance()->delete('error');
			}
			
			$result = ['error' => false];			
			$slug = $this->request->param('slug');
			
			if (!$slug)
			{
				throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');			
			}
			
			$user = new Model_User();
			$user = $user->getByColumn('slug', $slug);
			
			if (!$user->loaded())
			{
				throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');
			}
					
			$this->context->user = $user;
			$this->context->title = 'Szabadúszó profil ' . $user->name();
						
			$authorization = new Authorization_User($user);
			
			//$this->context->authorization = $authorization;
			$this->context->canRate = (int) $authorization->canRate();
			$this->context->canEdit = $canEdit = (int) $authorization->canEdit();
			$this->context->canSeeProjectNotification = $canSeeProjectNotification = (int) $authorization->canSeeProjectNotification();			
			
			if ($canEdit)
			{
				$this->context->editUrl = Viewhelper_User::getEditUrl($user);
			}			
			
			$logged 					= Auth::instance()->get_user();
            $myRating                   = Model_User_Rating::getRating($logged, $this->context->user);
			$this->context->myRating	= ($myRating) ? $myRating : '-';
			
			if ($canSeeProjectNotification) 
			{
				$this->context->industries  = Viewhelper_User::getIndustriesForProfile($user);
				$this->context->professions = Viewhelper_User::getProfessionsForProfile($user);
				$this->context->skills      = Viewhelper_User::getSkillsForProfile($user);

                $this->context->container = Project_Notification_Search_View_Container_Factory_User::createContainer([
                    'professions'           => $this->context->professions,
                    'skills'                => $this->context->skills,
                    'skill_relation'        => $user->skill_relation,
                    'current'               => 'complex',
                    'industries'            => $this->context->industries
                ]);
			}
		}		
		catch (HTTP_Exception_404 $exnf)	// 404 Nof found
		{
			Session::instance()->set('error', $exnf->getMessage());						
			$this->defaultExceptionRedirect($exnf);
		}
		catch (Exception $ex)		// Altalanos hiba
		{
			Session::instance()->set('error', __('defaultErrorMessage'));
				
			// Logbejegyzest keszit
			$errorLog = new Model_Errorlog();
			$errorLog->log($ex);
				
			$result = ['error' => true];
		}	
	}
	
	function action_projectownerprofile()
	{
		try
		{	
			if (Session::instance()->get('error'))
			{
				$this->context->session_error = Session::instance()->get('error');
				Session::instance()->delete('error');
			}
			
			$this->context->title = 'Megbízó profil';
			$result = ['error' => false];				
			$slug = $this->request->param('slug');
			
			if (!$slug)
			{
				throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');
			}
				
			$user = new Model_User();
			$user = $user->getByColumn('slug', $slug);

            if (!$user->loaded())
			{
				throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');
			}
			
			$authorization = new Authorization_User($user);						
				
			$this->context->user = $user;										
				
			$this->context->canRate = (int) $authorization->canRate();
			$this->context->canEdit = (int) $authorization->canEdit();
			$this->context->editUrl = Viewhelper_User::getEditUrl($user);
				
			$logged = Auth::instance()->get_user();
			
			$project	= new Model_Project();
			$industry	= new Model_Industry();
			
			$projects 	= $user->getProjects();
			$relations	= [];
			$salaries	= [];
			$users 		= [];
			 
			foreach ($projects as $pr)
			{
                /**
                 * @var $pr ORM
                 */
				$relations[$pr->project_id]	= $pr->getRelations();
				$salaries[$pr->project_id]	= Viewhelper_Project::getSalary(new Entity_Project($pr->project_id));
				$users[$pr->project_id]		= $user;
			}

			$entity = new Entity_Project();
			$this->context->projects 	= $entity->getEntitiesFromModels($projects);
			$this->context->relations	= $relations;
			$this->context->salaries	= $salaries;
			$this->context->users		= $users;
			$this->context->industries	= $industry->getAll();

            $ownRating = Model_User_Rating::getRating($logged, $user);
			$this->context->myRating = ($ownRating) ? $ownRating : '-';
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

	public function action_freelancers()
	{
		try
		{
			$user			= new Model_User();
			$result 		= ['error' => false];						
			$authorization 	= new Authorization_User($user);
			
			$limit 			= Kohana::$config->load('users')->get('pagerLimit');
			$currentPage 	= $this->request->param('page') ? $this->request->param('page') : 1;
			$offset			= (($currentPage - 1) * $limit);
				
			if (!$authorization->canSeeFreelancers())
			{
				throw new HTTP_Exception_403('Nincs jogosultságod a Szabadúszók megtekintéséhez');
			}							
        
	        if ($this->request->method() == Request::POST)
	        {        	
	        	$this->context->needPager = false;
	        	
	        	if (Input::post('complex'))
	        	{
	        		$postProfessionIds	= Input::post('professions', []);
	        		$postSkillIds 		= Input::post('skills', []);	        		 
	        		$postProfessions	= [];
	        		$postSkills 		= [];	        		 
	        		$profession			= new Model_Profession();
	        		$skill 				= new Model_Skill();
	        		 
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
	        	
	        	$users = $user->search(Input::post_all());
	        }    
	        else
	        {
	        	$this->context->needPager	= true;
	        	$users 						= $user->getAll();

	        	/**
	        	 * @todo KISZEDNI A NEVET. EZ CSAK A 07.06 LAUNCH UTAN KELLETT, AMIKOR MEG FELKESZ PROFILOK VOLTAK
	        	 * @todo RENDEZES ERTEKELES ALAPJAN
	        	 */

	        	$withPicture	= AB::select()->from($users)->where('profile_picture_path', '!=', '')->and_where('type', '=', 1)->order_by('lastname')->execute()->as_array();
	        	$withoutPicture	= AB::select()->from($users)->where('profile_picture_path', '=', '')->and_where('type', '=', 1)->order_by('lastname')->execute()->as_array();
	        	//$withoutName	= AB::select()->from($users)->where('firstname', '=', '')->order_by('lastname')->execute()->as_array();
	        	$merged			= Arr::merge($withPicture, $withoutPicture);	

	        	$users 			= AB::select()->from($merged)->where('user_id', '!=', '')->limit($limit)->offset($offset)->execute()->as_array();
	        }	                	        	     
	                	        
	        $industry				= new Model_Industry();	        	        
	        $countUsers				= count($users);

            $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
            $countAllUsers = $freelancer->getCount();
	        $this->context->users	= $users;
	        $this->context->title	= $countAllUsers . ' Szabadúszó egy helyen';
	        
	        /**
	         * @var $pagesFloat				Lapok szama tizedes szamban. Projektek szama / limit. Pl.: 33 / 10 = 3.3 
	         * @var $pagesCount				Lapok szama egesz szamban. Pl. 3.3 => 3
	         * @var $pagesDecimalRemainder	A ket ertek kulonbsege, tehat a tizedes maradek. Pl.: 3.3 - 3 = 0.3
	         * 
	         * Ha van maradek, akkor egyel tobb lap van
	         */
	        
	        $pagesFloat 			= $countAllUsers / $limit;			// Pl.: 33 / 10 = 3.3
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
	        $this->context->countUsers			= $countUsers;
	        $this->context->countAllUsers		= $countAllUsers;
	        $this->context->industries	= $industry->getAll();
	        
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
		catch (HTTP_Exception_403 $exforbidden)		// Forbidden, nincs jogosultsag
		{
			$exforbidden->setRedirectRoute($this->request->route());
			$exforbidden->setRedirectSlug($this->request->param('slug'));
				
			Session::instance()->set('error', $exforbidden->getMessage());
			$this->defaultExceptionRedirect($exforbidden);
		}
		catch (Exception $ex)
		{
			$errorLog = new Model_Errorlog();
			$errorLog->log($ex);
		
			$result = ['error' 	=> true];
		}
		finally
		{
			if ($this->request->method() == Request::POST)
			{
				Model_Database::trans_end([!Arr::get($result, 'error')]);
			}
		}
	}
    
    public function action_logout()
    {
        // Kijelentkeztetes
        Auth::instance()->logout(true, true);

        // Atiranyitas kezdooldalra
        HTTP::redirect(Route::url('home'));
    }
    
    public function action_ajax()
    {        	    
    	try 
    	{
    		$result = ['error' => false];
    		
    		//Model_Database::trans_start();      		
    		
    		if (!$this->request->is_ajax())
    		{
    			throw new HTTP_Exception_400('Only Ajax');
    		}
    		 
    		$this->auto_render = false;
    		$user = new Model_User();
    		 
    		switch ($this->request->param('actiontarget'))
    		{
    			// Felhasznalo ertekeles
    			case 'rate':
    				$user = $user->getById(Input::post('user_id'));
    				$data = $user->rate(Input::post('rating'));
    				$json = json_encode($data);
    				
    				break;
    		
    			// Porjekt ertesito beallitasa
    			case 'saveProjectNotification':
    			    $user = Entity_User::createUser(Entity_User::TYPE_FREELANCER, Input::post('user_id'));
    				//$user = $user->getById(Input::post('user_id'));
    				$data = $user->saveProjectNotification(Input::post_all());
    				$json = json_encode($data);
    				
    				break;
    		
    			default:
    				throw new HTTP_Exception_400('Action target not found');
    		}
    		
    		$this->response->body($json);
    		
    	}
    	catch (Exception $ex)		// Altalanos hiba
		{			
			// Logbejegyzest keszit
			$errorLog = new Model_Errorlog();
			$errorLog->log($ex);
				
			$result = ['error' => true];
		}
		finally
		{
			Model_Database::trans_end([!Arr::get($result, 'error')]);
		}		     	    	        
	}
}
