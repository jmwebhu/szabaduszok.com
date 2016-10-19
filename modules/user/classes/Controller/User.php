<?php

class Controller_User extends Controller_DefaultTemplate
{
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
}
