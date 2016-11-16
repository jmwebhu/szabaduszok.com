<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller_DefaultTemplate
{
	public function action_index()
	{								
		Session::instance()->delete('error');
		$this->context->noHeader = true;

		$project = new Model_Project();

        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $employer   = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);

        $this->context->countOfFreelancers		= $freelancer->getCount();
        $this->context->countOfProjectOwners	= $employer->getCount();
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
			$error = false;
			$this->context->title = 'Írj nekünk!';
			
			if ($this->request->method() == Request::POST) {
				Model_Database::trans_start();
				$post 		= Input::post_all();
				$contact	= new Model_Contact();
				
				$validation = Validation::factory(Input::post_all());
				$validation->rule('name', 'not_empty');
				$validation->rule('email', 'not_empty');
				$validation->rule('email', 'email');
				$validation->rule('message', 'not_empty');

				if (!$validation->check()) {
					throw new Validation_Exception($validation, 'Kérlek minden mezőt tölts ki');
				}

				$submit = $contact->submit(Input::post_all());
				if (Arr::get($submit, 'error')) {
					throw new Exception(Arr::get($submit, 'messages'));
				}

				$this->context->message = 'Köszönjük, hogy írtál nekünk, a lehető leghamarabb válaszolni fogunk!';
			}
		} catch (Validation_Exception $vex) {
			$this->context->message = $vex->getMessage();
			$error = true;

		} catch (Exception $ex) {
			$this->context->message = __('defaultErrorMessage');	
			Log::instance()->addException($ex);
			$error = true;

		} finally {
			if ($this->request->method() == Request::POST) {
				Model_Database::trans_end([!$error]);	
				$this->context->error = $error;		
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
