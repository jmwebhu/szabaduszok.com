<?php

abstract class Controller_User_Profile extends Controller_User implements Controller_User_Polymorph_Behaviour_Profile
{
    public function action_index()
    {
        try {
            $loggedIn = Auth::instance()->get_user();
            $this->throwForbiddenExceptionIfNot($loggedIn->loaded());

            $this->handleSessionError();
            $slug = $this->request->param('slug');
            $this->throwNotFoundExceptionIfNot($slug);

            $userModel = $this->getUserModel();
            $userModel = $userModel->getBySlug($slug);
            $this->throwNotFoundExceptionIfNot($userModel->loaded());

            $this->_user        = Entity_User::createUser($this->getUserType(), $userModel);
            $this->_viewhelper  = Viewhelper_User_Factory::createViewhelper($this->_user, Viewhelper_User::ACTION_CREATE);

            $this->setContext();

        } catch (HTTP_Exception_404 $exnf) {
            Session::instance()->set('error', $exnf->getMessage());
            $this->defaultExceptionRedirect($exnf);

        } catch (HTTP_Exception_403 $exforbidden) {
            $exforbidden->setRedirectRoute($this->request->route());
            $exforbidden->setRedirectSlug($this->request->param('slug'));

            Session::instance()->set('error', $exforbidden->getMessage());
            $this->defaultExceptionRedirect($exforbidden);

        } catch (Exception $ex) {
            $this->context->error = __('defaultErrorMessage');
            Log::instance()->addException($ex);
        }
    }

    protected function setContext()
    {
        $this->context->user            = $this->_user;
        $this->context->title           = $this->getTitle() . ' ' . $this->_user->getName();
        $this->context->profileTitle    = $this->getTitle();

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