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

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->_project = new Entity_Project();
        $this->_project = $this->_project->getBySlug($request->param('slug'));
        $this->_user    = new Model_User();

        parent::__construct($request, $response);
    }

    public function action_index()
    {
        try {
            $this->throwExceptionIfNotVisible();
            $this->setContext();

        } catch (HTTP_Exception_404 $exnf) {
            $this->handleNotFoundException($exnf);

        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    /**
     * @throws HTTP_Exception_404
     */
    protected function throwExceptionIfNotVisible()
    {
        if (!$this->_project->isVisible()) {
            throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen projekt');
        }
    }

    /**
     * @param HTTP_Exception_404 $exnf
     */
    protected function handleNotFoundException(HTTP_Exception_404 $exnf)
    {
        Session::instance()->set('error', $exnf->getMessage());
        $this->defaultExceptionRedirect($exnf);
    }

    /**
     * @param Exception $ex
     */
    protected function handleException(Exception $ex)
    {
        $this->context->error = __('defaultErrorMessage');
        Log::instance()->addException($ex);
    }

    protected function setContext()
    {
        $this->_user                = $this->_user->getById($this->_project->getUserId());
        $this->context->user        = $this->_user;

        $myRating					= Auth::instance()->get_user()->getMyRating($this->_user);
        $this->context->myRating	= $myRating;

        if (!$myRating) {
            $this->context->myRating = '-';
        }

        $this->context->project     = $this->_project;
        $this->context->title       = 'Szabadúszó projekt ' . $this->_project->getName();
        $this->context->salary      = Viewhelper_Project::getSalary($this->_project);

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