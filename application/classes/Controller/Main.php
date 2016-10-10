<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller_DefaultTemplate
{
	public function action_index()
	{								
		Session::instance()->delete('error');
		$this->context->noHeader = true;
		
		$user = new Model_User();
		$project = new Model_Project();
		
		$this->context->countOfFreelancers		= $user->getCountByType(1);
		$this->context->countOfProjectOwners	= $user->getCountByType(2);
		$this->context->countOfProjects 		= $project->getCount();
		$this->context->landingPage				= Input::get('landing');
	}
	
	public function action_interested()
	{            
            $this->context->title = 'Csatlakozz!';
	}					
	
	public function action_howitworks()
	{
		$this->context->title = 'Hogyab működik?';
	}
	
	public function action_contactus()
	{
		try
		{
			$result = ['error' => false];
			$this->context->title = 'Írj nekünk!';
			
			if ($this->request->method() == Request::POST)
			{
				Model_Database::trans_start();
				$contact	= new Model_Contact();
				
				$submit = $contact->submit(Input::post_all());
				
				if (Arr::get($submit, 'error'))
				{
					throw new Exception(Arr::get($submit, 'messages'));
				}
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
				$this->context->message = 'Köszönjük, hogy írtál nekünk, a lehető leghamarabb válaszolni fogunk!';
			}
		}
	}
	
	public function action_projectownerlist()
	{
		$this->context->title = 'Megbízók böngészése';
	}
	
	public function action_freelancerlist()
	{
		$this->context->title = 'Szabadúszók böngészése';
	}		

	public function action_contact()
	{
		$this->context->title = 'Kapcsolat - Szabadúszók';
	}

	public function action_termsofuse()
	{
		$this->context->title = 'Felhasználási feltételek - Szabadúszók';
	}

	public function action_privacy()
	{
		$this->context->title = 'Adatvédelmi nyilatkozat - Szabadúszók';
	}	
}
