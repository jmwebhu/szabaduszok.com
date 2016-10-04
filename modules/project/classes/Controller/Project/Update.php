<?php

class Controller_Project_Update extends Controller_Project
{
    /**
     * @var Authorization_Project
     */
    private $_authorization;

    /**
     * @var Model_User
     */
    private $_user;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->_project = $this->_project->getBySlug($request->param('slug'));
        $this->_user    = Auth::instance()->get_user();
    }

    public function action_index()
    {
        try {
            $this->throwExceptionIfError();
            $this->setContext();
            $this->handlePostRequest();

        } catch (HTTP_Exception_403 $exforbidden) {
            $this->handleForbiddenException($exforbidden);

        } catch (HTTP_Exception_404 $exnf) {
            $this->handleNotFoundException($exnf);

        } catch (Exception $ex) {
            $this->handleException($ex);

        } finally {
            $this->defaultFinally();
        }
    }

    /**
     * @throws HTTP_Exception_403
     * @throws HTTP_Exception_404
     */
    protected function throwExceptionIfError()
    {
        $this->throwExceptionIfNotVisible();
        $this->throwExceptionIfCannotEdit();
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
     * @throws HTTP_Exception_403
     */
    protected function throwExceptionIfCannotEdit()
    {
        $this->_authorization = new Authorization_Project($this->_project->getModel());

        if (!$this->_authorization->canEdit()) {
            throw new HTTP_Exception_403('Nincs jogosultságod szerkeszteni a projektet');
        }
    }

    protected function setContext()
    {
        $title = Viewhelper_Project::getPageTitle('edit') . $this->_project->getName();

        $this->context->pageTitle   = $title;
        $this->context->title       = $title;
        $this->context->project     = $this->_project;

        $this->context->email       = Viewhelper_Project::getEmail($this->_user, 'edit', $this->_project);
        $this->context->phonenumber = Viewhelper_Project::getPhonenumber($this->_user, 'edit', $this->_project);

        $this->context->formAction  = Viewhelper_Project::getFormAction('edit', $this->_project);
        $this->context->hasCancel   = (int)$this->_authorization->hasCancel();
        $this->context->user        = $this->_user;

        $this->setContextRelations();
    }

    protected function setContextRelations()
    {
        $industry   = new Model_Industry();
        $profession = new Model_Profession();
        $skill      = new Model_Skill();

        $this->context->industries  = $industry->getAll();
        $this->context->professions = $profession->getAll();
        $this->context->skills      = $skill->getAll();
    }

    protected function handlePostRequest()
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_start();
            $this->_project->submit(Input::post_all());
        }
    }

    /**
     * @param HTTP_Exception_403 $exforbidden
     */
    protected function handleForbiddenException(HTTP_Exception_403 $exforbidden)
    {
        $exforbidden->setRedirectRoute($this->request->route());
        $exforbidden->setRedirectSlug($this->request->param('slug'));

        Session::instance()->set('error', $exforbidden->getMessage());
        $this->defaultExceptionRedirect($exforbidden);
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
        Log::instance()->addException($ex);
        $this->context->error = __('defaultErrorMessage');
        $this->_error = true;
    }
}