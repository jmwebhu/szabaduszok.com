<?php

class Controller_Project_Profile extends Controller_DefaultTemplate
{
    /**
     * @var Entity_Project
     */
    private $_project;

    /**
     * @var Model_User
     */
    private $_user;

    public function __construct(Request $request, Response $response)
    {
        $this->_project = new Entity_Project();
        $this->_user    = new Model_User();

        parent::__construct($request, $response);
    }

    public function action_index()
    {
        try {
            $slug = $this->request->param('slug');

            if (!$slug) {
                throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen projekt');
            }

            $this->_project = $this->_project->getBySlug($slug);

            if (!$this->_project->isVisible()) {
                throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen projekt');
            }

            $this->setContext();

        } catch (HTTP_Exception_404 $exnf) {
            Session::instance()->set('error', $exnf->getMessage());
            $this->defaultExceptionRedirect($exnf);

        } catch (Exception $ex) {
            $this->context->error = __('defaultErrorMessage');
            Log::instance()->addException($ex);
        }
    }

    protected function setContext()
    {
        $this->_user                = $this->_user->getById($this->_project->getUserId());
        $this->context->user        = $this->_user;

        $loggedUser 			    = Auth::instance()->get_user();
        $myRating					= $loggedUser->getMyRating($this->_user);
        $this->context->myRating	= ($myRating) ? $myRating : '-';

        $this->context->project     = $this->_project->getModel();
        $this->context->title       = 'Szabadúszó projekt ' . $this->_project->getName();
        $this->context->salary      = Viewhelper_Project::getSalary($this->_project->getModel());

        $this->setContextAuthorization();
        $this->setContextRelations();
    }

    protected function setContextAuthorization()
    {
        $authorization		        = new Authorization_Project($this->_project->getModel());
        $authorizationUser	        = new Authorization_User($this->_user);

        $this->context->canRate     = (int)$authorizationUser->canRate();
        $this->context->canEdit     = (int)$authorization->canEdit();
        $this->context->canDelete   = (int)$authorization->canDelete();
    }

    protected function setContextRelations()
    {
        $relations                  = $this->_project->getModel()->getRelations();

        $this->context->industries  = Arr::get($relations, 'industries');
        $this->context->professions = Arr::get($relations, 'professions');
        $this->context->skills      = Arr::get($relations, 'skills');
    }
}