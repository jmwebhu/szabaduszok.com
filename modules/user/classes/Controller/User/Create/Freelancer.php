<?php

class Controller_User_Create_Freelancer extends Controller_DefaultTemplate
{
    /**
     * @var string
     */
    private $_email;

    /**
     * @var bool
     */
    private $_error;

    /**
     * @var Entity_User
     */
    private $_user;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->_email = Input::get('email');
        $this->_error = false;
    }

    public function action_index()
    {
        try {
            $this->setContext();
            $this->handleSignup();
            Model_Leadmagnet::sendTo($this->_email, Entity_User::TYPE_FREELANCER);

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

                    header('Location: ' . Route::url('freelancerProfile', ['slug' => $this->_user->getSlug()]), true, 302);
                    die();
                }
            }
        }
    }

    protected function setContext()
    {
        $this->context->pageTitle			= $this->context->title = Viewhelper_User::getPageTitleFreelancer();
        $this->context->hasPrivacyCheckbox	= Viewhelper_User::hasPrivacyCheckbox();
        $this->context->passwordText		= Viewhelper_User::getPasswordText();
        $this->context->hasIdInput			= Viewhelper_User::hasIdInput();
        $this->context->formAction			= Viewhelper_User::getFormActionFreelancer();
        $this->context->hasPasswordRules	= Viewhelper_User::hasPasswordRules();

        $profile							= new Model_Profile();
        $this->context->profiles			= $profile->where('is_active', '=', 1)->find_all();

        $this->context->email           = $this->_email;
        $this->context->landingPageName = Input::get('landing_page_name');

        $industry                   = new Model_Industry();
        $this->context->industries  = $industry->getAll();
    }

    protected function handleSignup()
    {
        $signup         = new Model_Signup();
        $signup->createIfHasEmail($this->_email);
    }

    protected function handlePostRequest()
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_start();

            $this->_user = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
            $this->_user->submit(Input::post_all());

            $this->_error = false;

            Auth_ORM::instance()->force_login($this->_user->getModel());
        }
    }
}