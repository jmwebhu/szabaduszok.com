<?php

class Controller_Project extends Controller_DefaultTemplate
{
    private $_jsonResponse;

    /**
     * Projekt modositasa
     * 'Modositom' gomb megnyomasakor jelenik meg az oldal
     */
    public function action_update()
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

    public function action_profile()
    {    	
    	try
    	{    	    		
    		$result = ['error' => false];
    		$slug = $this->request->param('slug');
    			
    		if (!$slug)
    		{
    		    echo Debug::vars('193');
                exit;
    			throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen projekt');
    		}
    			    		    		
    		$project	= new Model_Project();
    		$user 		= new Model_User();
    		
    		/**
    		 * @var $project Model_Project
    		 */
    		$project = $project->getBySlug($slug);
    		$project->with('user');
    			
    		if (!$project->loaded() || !$project->is_active) {
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
    		
    		$relations = $project->getRelations();
    		
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

    public function action_ajax()
    {
        try {
            Model_Database::trans_start();
            $result = ['error' => false];

            if (!$this->request->is_ajax()) {
                throw new HTTP_Exception_400('Only Ajax');
            }

            $this->auto_render = false;

            switch ($this->request->param('actiontarget')) {
                case 'inactivate':
                    $project = new Model_Project(Input::post('id'));
                    $this->_jsonResponse = json_encode($project->inactivate());

                    break;

                case 'professionAutocomplete':
                    $profession = new Model_Profession();
                    $this->_jsonResponse = json_encode($profession->relationAutocomplete(Input::get('term')));

                    break;

                case 'skillAutocomplete':
                    $skill = new Model_Skill();
                    $this->_jsonResponse = json_encode($skill->relationAutocomplete(Input::get('term')));

                    break;

                default:
                    throw new HTTP_Exception_400('Action target not found');
            }
        } catch (Exception $ex) {
            Log::instance()->addException($ex);

            $result = ['error' => true];
            $this->_jsonResponse = json_encode($result);
            $this->response->status(500);
        } finally {
            Model_Database::trans_end([!Arr::get($result, 'error')]);
        }

        $this->response->body($this->_jsonResponse);
    }
}
