<?php

abstract class Controller_User_Profile extends Controller_User_Base
{
    /**
     * @var bool
     */
    protected $_error;

    /**
     * @var Entity_User
     */
    protected $_user;

    /**
     * @var Viewhelper_User
     */
    protected $_viewhelper;

    /**
     * @return int
     */
    abstract protected function getUserType();

    /**
     * @return string
     */
    abstract protected function getTitle();

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_error = false;
    }

    public function action_index()
    {
        try {
            $this->handleSessionError();
            $slug = $this->request->param('slug');

            $this->throwNotFoundExceptionIfNot($slug);

            $userModel = new Model_User();
            $userModel = $userModel->getBySlug($slug);
            $this->throwNotFoundExceptionIfNot($userModel->loaded());

            $this->_user        = Entity_User::createUser($this->getUserType(), $userModel->user_id);
            $this->_viewhelper  = Viewhelper_User_Factory::createViewhelper($this->_user, Viewhelper_User::ACTION_CREATE);

            $this->setContext();

        } catch (HTTP_Exception_404 $exnf) {
            Session::instance()->set('error', $exnf->getMessage());
            $this->defaultExceptionRedirect($exnf);

        } catch (Exception $ex) {
            $this->context->error = __('defaultErrorMessage');
            Log::instance()->addException($ex);
        }
    }

    protected function handleSessionError()
    {
        if (Session::instance()->get('error')) {
            $this->context->session_error = Session::instance()->get('error');
            Session::instance()->delete('error');
        }
    }

    protected function setContext()
    {
        $this->context->user        = $this->_user;
        $this->context->title       = $this->getTitle();

        $authorization              = new Authorization_User($this->_user->getModel());

        $this->context->canRate     = (int)$authorization->canRate();
        $this->context->canEdit     = (int)$authorization->canEdit();

        if ($this->context->canEdit) {
            $this->context->editUrl = $this->_viewhelper->getEditUrl();
        }

        $loggedUser                 = Auth::instance()->get_user();
        $ownRating                  = Model_User_Rating::getRating($loggedUser, $this->_user->getModel());
        $this->context->myRating    = ($ownRating) ? $ownRating : '-';
    }
}