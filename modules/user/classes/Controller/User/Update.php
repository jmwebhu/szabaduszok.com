<?php

abstract class Controller_User_Update extends Controller_User_Write
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

            $userModel = AB::select()->from(new Model_User())->where('slug', '=', $slug)->limit(1)->execute()->current();
            $this->_user = Entity_User::createUser($this->getUserType(), $userModel->user_id);
            $this->throwNotFoundExceptionIfNot($this->_user->loaded());

            $this->_authorization = new Authorization_User($userModel);
            $this->throwForbiddenExceptionIfNot($this->_authorization->canEdit());

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

    /**
     * @param bool $expression
     * @throws HTTP_Exception_404
     */
    protected function throwNotFoundExceptionIfNot($expression)
    {
        if (!$expression) {
            throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');
        }
    }

    /**
     * @param bool $expression
     * @throws HTTP_Exception_403
     */
    protected function throwForbiddenExceptionIfNot($expression)
    {
        if (!$expression) {
            throw new HTTP_Exception_403('Nincs jogosultságod a profil szerkesztéséhez');
        }
    }

    protected function setContext()
    {
        $this->_viewhelper  = Viewhelper_User_Factory::createViewhelper($this->_user, Viewhelper_User::ACTION_EDIT);

        $industry                           = new Model_Industry();
        $this->context->title               = $this->_viewhelper->getPageTitle();
        $this->context->hasCancel           = (int)$this->_authorization->hasCancel();
        $this->context->user                = $this->_user;
        $this->context->industries          = $industry->getAll();
        $this->context->hasPrivacyCheckbox	= $this->_viewhelper->hasPrivacyCheckbox();
        $this->context->pageTitle			= $this->_viewhelper->getPageTitle();
        $this->context->passwordText		= $this->_viewhelper->getPasswordText();
        $this->context->hasIdInput			= $this->_viewhelper->hasIdInput();
        $this->context->formAction			= $this->_viewhelper->getFormAction();
        $this->context->hasPasswordRules	= $this->_viewhelper->hasPasswordRules();
        $this->context->hasPicture			= $this->_viewhelper->hasPicture();
    }

    protected function handlePostRequest()
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_start();
            $this->_user->submit(Input::post_all());
        }
    }
}