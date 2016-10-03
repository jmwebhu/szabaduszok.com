<?php

class Controller_Project_Create extends Controller_Project
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

        $this->_authorization   = new Authorization_Project();
        $this->_user            = Auth::instance()->get_user();
    }

    public function action_index()
    {
        try {
            if (!$this->_authorization->canCreate()) {
                throw new HTTP_Exception_403('Nincs jogosultságod új projektet indítani');
            }

            $this->setContext();

            if ($this->request->method() == Request::POST) {
                $this->handlePostRequest();
            }

        } catch (HTTP_Exception_403 $exforbidden) {
            $exforbidden->setRedirectRoute($this->request->route());
            $exforbidden->setRedirectSlug($this->request->param('slug'));

            Session::instance()->set('error', $exforbidden->getMessage());
            $this->defaultExceptionRedirect($exforbidden);

            $this->_error = true;

        } catch (Exception $ex) {
            $this->context->error = __('defaultErrorMessage');
            Log::instance()->addException($ex);

            $this->_error = true;

        } finally {
            $this->defaultFinally();
        }
    }

    protected function setContext()
    {
        $industry                   = new Model_Industry();

        $this->context->pageTitle   = $this->context->title = Viewhelper_Project::getPageTitle();
        $this->context->hasIdInput  = Viewhelper_Project::hasIdInput();
        $this->context->formAction  = Viewhelper_Project::getFormAction();
        $this->context->email       = Viewhelper_Project::getEmail($this->_user);
        $this->context->phonenumber = Viewhelper_Project::getPhonenumber($this->_user);
        $this->context->industries  = $industry->getAll();
    }

    protected function handlePostRequest()
    {
        Model_Database::trans_start();
        $this->_project->submit(Input::post_all());
    }
}