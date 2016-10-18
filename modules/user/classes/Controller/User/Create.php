<?php

abstract class Controller_User_Create extends Controller_DefaultTemplate
{
    /**
     * @var string
     */
    protected $_email;

    /**
     * @var bool
     */
    protected $_error;

    /**
     * @var Entity_User
     */
    protected $_user;

    /**
     * @var
     */
    protected $_viewhelper;

    /**
     * @return int
     */
    abstract protected function getUserType();

    /**
     * @return string
     */
    abstract protected function getProfileUrl();

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->_email       = Input::get('email');
        $this->_error       = false;
        $this->_user        = Entity_User::createUser($this->getUserType());
        $this->_viewhelper  = Viewhelper_User_Factory::createViewhelper($this->_user, Viewhelper_User::ACTION_CREATE);
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
        $this->context->pageTitle			= $this->context->title = $this->_viewhelper->getPageTitle();
        $this->context->hasPrivacyCheckbox	= $this->_viewhelper->hasPrivacyCheckbox();
        $this->context->passwordText		= $this->_viewhelper->getPasswordText();
        $this->context->hasIdInput			= $this->_viewhelper->hasIdInput();
        $this->context->formAction			= $this->_viewhelper->getFormAction();
        $this->context->hasPasswordRules	= $this->_viewhelper->hasPasswordRules();

        $this->context->email               = $this->_email;
        $this->context->landingPageName     = Input::get('landing_page_name');

        $industry                           = new Model_Industry();
        $this->context->industries          = $industry->getAll();
    }
}