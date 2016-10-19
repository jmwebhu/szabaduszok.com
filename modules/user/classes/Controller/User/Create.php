<?php

abstract class Controller_User_Create extends Controller_User_Write
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
        $this->_email = Input::get('email');
    }

    public function action_index()
    {
        try {
            $this->setContext();
            $this->handleSignup();
            Model_Leadmagnet::sendTo($this->_email, $this->getUserType());

            $this->handlePostRequest();

        } catch (Exception_UserRegistration $exur) {
            $this->context->error = $exur->getMessage();
            $this->_error = true;

        } catch (Exception $ex) {
            $this->context->error = __('defaultErrorMessage');
            $this->_error = true;

            Log::instance()->addException($ex);

        } finally {
            if ($this->request->method() == Request::POST) {
                Model_Database::trans_end([!$this->_error]);

                if (!$this->_error) {
                    $id             = Arr::get(Input::post_all(), 'user_id', false);
                    $mailinglist    = Gateway_Mailinglist_Factory::createMailinglist($this->_user);
                    $mailinglist->add((bool)$id);

                    header('Location: ' . $this->getProfileUrl(), true, 302);
                    die();
                }
            }
        }
    }

    protected function setContext()
    {
        $viewhelper = Viewhelper_User_Factory::createViewhelper($this->_user, Viewhelper_User::ACTION_CREATE);
        $this->context->pageTitle			= $this->context->title = $viewhelper->getPageTitle();
        $this->context->hasPrivacyCheckbox	= $viewhelper->hasPrivacyCheckbox();
        $this->context->passwordText		= $viewhelper->getPasswordText();
        $this->context->hasIdInput			= $viewhelper->hasIdInput();
        $this->context->formAction			= $viewhelper->getFormAction();
        $this->context->hasPasswordRules	= $viewhelper->hasPasswordRules();

        $this->context->email               = $this->_email;
        $this->context->landingPageName     = Input::get('landing_page_name');

        $industry                           = new Model_Industry();
        $this->context->industries          = $industry->getAll();
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

            $this->_user = Entity_User::createUser($this->getUserType());
            $this->_user->submit(Input::post_all());

            $this->_error = false;

            Auth_ORM::instance()->force_login($this->_user->getModel());
        }
    }
}