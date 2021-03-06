<?php

abstract class Controller_User_Create extends Controller_User implements Controller_User_Polymorph_Behaviour_Write
{
    /**
     * @var string
     */
    protected $_email;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_email   = Input::get('email');
        $this->_user    = Entity_User::createUser($this->getUserType());
    }

    public function action_index()
    {
        try {
            $this->setContext();
            $this->handleSignup();
            Model_Leadmagnet::sendTo($this->_email, $this->getUserType());

            $this->handlePostRequest();

        } catch (ORM_Validation_Exception $ovex) {
            $this->_error = true;
            $this->handleValidationException($ovex, $this->getUrl());
            
        } catch (Exception_UserRegistration $exur) {
            $this->context->error = $exur->getMessage();
            $this->_error = true;

        } catch (Exception $ex) {
            $this->context->error = __('defaultErrorMessage');
            $this->_error = true;

            Log::instance()->addException($ex);

        } finally {
            if ($this->_error) {
                Auth_ORM::instance()->logout();
                Session::instance()->delete('auth_user');
            }

            $this->finallyRedirect($this->getProfileUrl());
        }
    }

    protected function setContext()
    {
        $viewhelper                         = Viewhelper_User_Factory::createViewhelper($this->_user, Viewhelper_User::ACTION_CREATE);
        $this->context->pageTitle			= $this->context->title = $viewhelper->getPageTitle();
        $this->context->hasPrivacyCheckbox	= $viewhelper->hasPrivacyCheckbox();
        $this->context->passwordText		= $viewhelper->getPasswordText();
        $this->context->hasIdInput			= $viewhelper->hasIdInput();
        $this->context->formAction			= $viewhelper->getFormAction();
        $this->context->hasPasswordRules	= $viewhelper->hasPasswordRules();

        $this->context->email               = $this->_email;
        $this->context->landingPageName     = Input::get('landing_page_name');

        $this->context->userType            = $this->getUserType();
        $this->context->needSteps           = true;

        $industry                           = new Model_Industry();
        $this->context->industries          = $industry->getAll();
        $this->context->AB                  = new AB();
        $this->context->professionModel     = new Model_Profession();
        $this->context->skillModel          = new Model_Skill();
    }

    protected function handleSignup()
    {
        $signup         = new Model_Signup();
        $signup->createIfHasEmail($this->_email, $this->getUserType());
    }

    protected function handlePostRequest()
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_start();

            $this->_user    = Entity_User::createUser($this->getUserType());
            $mailinglist    = Gateway_Mailinglist_Factory::createMailinglist($this->_user);
            $this->_user->submitUser(Input::post_all(), $mailinglist);

            $this->_error = false;

            Auth_ORM::instance()->force_login($this->_user->getModel());
        }
    }
}
