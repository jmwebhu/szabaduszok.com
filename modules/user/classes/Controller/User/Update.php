<?php

abstract class Controller_User_Update extends Controller_User implements Controller_User_Polymorph_Behaviour_Write
{
    /**
     * @var Authorization_User
     */
    protected $_authorization;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_user = Entity_User::createUser($this->getUserType());
    }

    public function action_index()
    {
        try {
            $slug = $this->request->param('slug');
            $this->throwNotFoundExceptionIfNot($slug);

            $userModel      = Model_User::createUser($this->getUserType());
            $userModel      = $userModel->getBySlug($slug);
            $this->_user    = Entity_User::createUser($this->getUserType(), $userModel);

            $this->throwNotFoundExceptionIfNot($this->_user->loaded());

            $this->_authorization = new Authorization_User($userModel);
            $this->throwForbiddenExceptionIfNot($this->_authorization->canEdit(), 'Nincs jogosultságod a profil szerkesztéséhez');

            $this->setContext();
            $this->handlePostRequest();

        } catch (ORM_Validation_Exception $ovex) {
            $this->handleValidationException($ovex, $this->getUrl());
            
        } catch (HTTP_Exception_404 $exnf) {
            Session::instance()->set('error', $exnf->getMessage());
            $this->defaultExceptionRedirect($exnf);

        } catch (HTTP_Exception_403 $exforbidden) {
            $exforbidden->setRedirectRoute($this->request->route());
            $exforbidden->setRedirectSlug($this->request->param('slug'));

            Session::instance()->set('error', $exforbidden->getMessage());
            $this->defaultExceptionRedirect($exforbidden);

        } catch (Exception_UserRegistration $exur) {
            $this->context->error   = $exur->getMessage();
            $this->_error           = true;

        } catch (Exception $ex) {
            Log::instance()->addException($ex);
            $this->_error = true;

        } finally {
            $this->finallyRedirect($this->getProfileUrl());
        }
    }

    protected function setContext()
    {
        $viewhelper  = Viewhelper_User_Factory::createViewhelper($this->_user, Viewhelper_User::ACTION_EDIT);
        $industry                           = new Model_Industry();
        $this->context->title               = $viewhelper->getPageTitle();
        $this->context->hasCancel           = (int)$this->_authorization->hasCancel();
        $this->context->user                = $this->_user;
        $this->context->industries          = $industry->getAll();
        $this->context->hasPrivacyCheckbox	= $viewhelper->hasPrivacyCheckbox();
        $this->context->pageTitle			= $viewhelper->getPageTitle();
        $this->context->passwordText		= $viewhelper->getPasswordText();
        $this->context->hasIdInput			= $viewhelper->hasIdInput();
        $this->context->formAction			= $viewhelper->getFormAction();
        $this->context->hasPasswordRules	= $viewhelper->hasPasswordRules();
        $this->context->hasPicture			= $viewhelper->hasPicture();
        $this->context->userType            = $this->getUserType();

        $this->context->needSteps           = false;
    }

    protected function handlePostRequest()
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_start();
            $mailinglist = Gateway_Mailinglist_Factory::createMailinglist($this->_user);
            $this->_user->submitUser(Input::post_all(), $mailinglist);
        }
    }
}