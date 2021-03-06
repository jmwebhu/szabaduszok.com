<?php

/**
 * Class Controller_Project_Profile
 *
 * Felelosseg: Projekt profil keres kiszolgalasa
 */

class Controller_Project_Profile extends Controller_User
{
    /**
     * @var Entity_Project
     */
    protected $_project;

    /**
     * @var Model_User
     */
    protected $_user;

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
            $loggedIn = Auth::instance()->get_user();
            $this->throwForbiddenExceptionIfNot($loggedIn->loaded(), 'A projekt megtekintéséhez kérjük lépj be');

            $this->throwExceptionIfNotVisible();
            $this->setContext();

        } catch (HTTP_Exception_403 $exforbidden) {
            $exforbidden->setRedirectRoute($this->request->route());
            $exforbidden->setRedirectSlug($this->request->param('slug'));
            
            $this->handleException400($exforbidden);

        } catch (HTTP_Exception_404 $exnf) {
            $this->handleException400($exnf);

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
     * @param HTTP_Exception
     */
    protected function handleException400(HTTP_Exception $ex)
    {
        Session::instance()->set('error', $ex->getMessage());
        $this->defaultExceptionRedirect($ex);
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
        $this->context->user        = Entity_User::createUser(Entity_User::TYPE_EMPLOYER, $this->_user);
        $this->context->profileTitle = 'Megbízó: ' . $this->context->user->getName();

        $myRating                   = Model_User_Rating::getRating(Auth::instance()->get_user(), $this->_user);
        $this->context->myRating	= $myRating;

        if (!$myRating) {
            $this->context->myRating = '-';
        }

        $this->context->project     = $this->_project;
        $this->context->title       = 'Szabadúszó projekt ' . $this->_project->getName();
        $this->context->salary      = Viewhelper_Project::getSalary($this->_project);

        $this->setContextAuthorization();
        $this->setContextRelations();
        $this->setContextPartners();
    }

    protected function setContextAuthorization()
    {
        $authorization		        = new Authorization_Project($this->_project->getModel());
        $authorizationUser	        = new Authorization_User($this->_user);

        $this->context->canRate     = (int)$authorizationUser->canRate();
        $this->context->canEdit     = (int)$authorization->canEdit();
        $this->context->canDelete   = (int)$authorization->canDelete();

        $this->context->conversationAuth = new Authorization_Conversation($this->_project->getModel()->user);
    }

    protected function setContextRelations()
    {
        $relations                  = $this->_project->getModel()->getRelations();

        $this->context->industries  = Arr::get($relations, 'industries');
        $this->context->professions = Arr::get($relations, 'professions');
        $this->context->skills      = Arr::get($relations, 'skills');
    }

    protected function setContextPartners()
    {
        $viewhelper         = new Viewhelper_Project_Partner($this->_project->getModel());
        $partnersEntity     = $viewhelper->getPartnersSeparatedByType();

        $this->context->partners = $partnersEntity;

        $projectPartnerModel            = new Model_Project_Partner();
        $this->context->projectPartner  = $projectPartnerModel->where('user_id', '=', Auth::instance()->get_user()->user_id)->and_where('project_id', '=', $this->_project->getProjectId())->find();

        $this->context->authorization   = new Authorization_User($this->_project->getModel(), Auth::instance()->get_user());
    }
}