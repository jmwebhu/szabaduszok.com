<?php

class Controller_User_Update_Freelancer extends Controller_User_Update
{
    public function action_index()
    {
        try {
            $slug = $this->request->param('slug');

            if (!$slug) {
                throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');
            }

            $userModel = AB::select()->from(new Model_User())->where('slug', '=', $slug)->limit(1)->execute()->current();
            $this->_user = Entity_User::createUser($this->getUserType(), $userModel->user_id);

            if (!$this->_user->loaded()) {
                throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');
            }

            $authorization = new Authorization_User($userModel);

            if (!$authorization->canEdit()) {
                throw new HTTP_Exception_403('Nincs jogosultságod a profil szerkesztéséhez');
            }

            $this->_viewhelper  = Viewhelper_User_Factory::createViewhelper($this->_user, Viewhelper_User::ACTION_EDIT);

            $industry							= new Model_Industry();
            $this->context->title				= 'Szabadúszó profil szerkesztés';
            $this->context->hasCancel			= (int) $authorization->hasCancel();
            $this->context->user				= $this->_user;
            $this->context->industries			= $industry->getAll();
            $this->context->hasPrivacyCheckbox	= $this->_viewhelper->hasPrivacyCheckbox();
            $this->context->pageTitle			= $this->_viewhelper->getPageTitle();
            $this->context->passwordText		= $this->_viewhelper->getPasswordText();
            $this->context->hasIdInput			= $this->_viewhelper->hasIdInput();
            $this->context->formAction			= $this->_viewhelper->getFormAction();
            $this->context->hasPasswordRules	= $this->_viewhelper->hasPasswordRules();
            $this->context->hasPicture			= $this->_viewhelper->hasPicture();
            $this->context->hasCv = $hasCv		= $this->_viewhelper->hasCv();

            $profile							= new Model_Profile();
            $this->context->profiles			= $profile->where('is_active', '=', 1)->find_all();
            $this->context->userProfileUrls		= $this->_user->getProfileUrls(new Model_User_Profile());

            if ($hasCv) {
                $parts                  = explode('.', $this->_user->getCvPath());
                $this->context->cvExt   = $parts[count($parts) - 1];
            }

            if ($this->request->method() == Request::POST) {
                $post = Input::post_all();
                Model_Database::trans_start();
                $this->_user->submit($post);
            }

        } catch (HTTP_Exception_404 $exnf) {
            Session::instance()->set('error', $exnf->getMessage());
            $this->defaultExceptionRedirect($exnf);

        } catch (HTTP_Exception_403 $exforbidden) {
            $exforbidden->setRedirectRoute($this->request->route());
            $exforbidden->setRedirectSlug($this->request->param('slug'));

            Session::instance()->set('error', $exforbidden->getMessage());
            $this->defaultExceptionRedirect($exforbidden);

        } catch (Exception_UserRegistration $exur) {
            $this->context->error = $exur->getMessage();
            $this->_error = true;

        } catch (Exception $ex) {
            Log::instance()->addException($ex);

            $this->_error = true;
        } finally {
            if ($this->request->method() == Request::POST) {
                Model_Database::trans_end([!$this->_error]);

                if (!$this->_error) {
                    $id = Arr::get($post, 'user_id');

                    $mailinglist = Gateway_Mailinglist_Factory::createMailinglist($this->_user);
                    $mailinglist->add((bool)$id);


                    header('Location: ' . $this->getProfileUrl(), true, 302);
                    die();
                }
            }
        }
    }

    /**
     * @return int
     */
    protected function getUserType()
    {
        return Entity_User::TYPE_FREELANCER;
    }

    /**
     * @return string
     */
    protected function getProfileUrl()
    {
        return Route::url('freelancerProfile', ['slug' => $this->_user->getSlug()]);
    }


}