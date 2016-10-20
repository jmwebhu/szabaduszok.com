<?php

abstract class Controller_User_Update extends Controller_User implements Controller_User_Polymorph_Behaviour_Write
{
    /**
     * @var Authorization_User
     */
    protected $_authorization;

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
            if ($this->request->method() == Request::POST) {
                Model_Database::trans_end([!$this->_error]);

                if (!$this->_error) {
                    $id = Arr::get(Input::post_all(), 'user_id');

                    $mailinglist = Gateway_Mailinglist_Factory::createMailinglist($this->_user);
                    $mailinglist->add((bool)$id);

                    header('Location: ' . $this->getProfileUrl(), true, 302);
                    die();
                }
            }
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
    }

    protected function handlePostRequest()
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_start();
            $this->_user->submit(Input::post_all());
        }
    }
}