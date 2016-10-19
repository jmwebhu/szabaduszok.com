<?php

class Controller_User_Profile_Freelancer extends Controller_User_Profile
{
    /**
     * @var Viewhelper_User
     */
    protected $_viewhelper;

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
            $entity             = Entity_User::createUser(Entity_User::TYPE_FREELANCER, $userModel->user_id);
            $this->_viewhelper  = Viewhelper_User_Factory::createViewhelper($entity, Viewhelper_User::ACTION_CREATE);

            if ($this->context->canEdit) {
                $this->context->editUrl = $this->_viewhelper->getEditUrl();
            }

            $logged 					= Auth::instance()->get_user();
            $myRating                   = Model_User_Rating::getRating($logged, $this->context->user);
            $this->context->myRating	= ($myRating) ? $myRating : '-';

            $this->setContextProjectNotification();

        } catch (HTTP_Exception_404 $exnf) {
            Session::instance()->set('error', $exnf->getMessage());
            $this->defaultExceptionRedirect($exnf);

        } catch (Exception $ex) {
            Session::instance()->set('error', __('defaultErrorMessage'));
            Log::instance()->addException($ex);

            $this->_error = true;
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
        $this->context->user    = $this->_user;
        $this->context->title   = 'Szabadúszó profil ' . $this->_user->getName();

        $authorization = new Authorization_User($this->_user->getModel());

        $this->context->canRate                     = (int)$authorization->canRate();
        $this->context->canEdit                     = (int)$authorization->canEdit();
        $this->context->canSeeProjectNotification   = (int)$authorization->canSeeProjectNotification();
    }

    protected function setContextProjectNotification()
    {
        if ($this->context->canSeeProjectNotification) {
            $this->context->industries  = $this->_viewhelper->getProjectNotificationRelationForProfile(new Model_Industry());
            $this->context->professions = $this->_viewhelper->getProjectNotificationRelationForProfile(new Model_Profession());
            $this->context->skills      = $this->_viewhelper->getProjectNotificationRelationForProfile(new Model_Skill());

            $this->context->container = Project_Notification_Search_View_Container_Factory_User::createContainer([
                'professions'       => $this->context->professions,
                'skills'            => $this->context->skills,
                'skill_relation'    => $this->_user->getSkillRelation(),
                'current'           => 'complex',
                'industries'        => $this->context->industries
            ]);
        }
    }
}