<?php

class Controller_User_Profile_Freelancer extends Controller_User_Profile
{
    /**
     * @return int
     */
    public function getUserType()
    {
        return Entity_User::TYPE_FREELANCER;
    }

    /**
     * @return Model_User_Freelancer
     */
    public function getUserModel()
    {
        return new Model_User_Freelancer();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Szabadúszó profil';
    }

    protected function setContext()
    {
        parent::setContext();
        $this->setContextProjectNotification();
        $this->setContextProjects();
    }

    private function setContextProjectNotification()
    {
        $authorization                              = new Authorization_User($this->_user->getModel());
        $this->context->canSeeProjectNotification   = (int)$authorization->canSeeProjectNotification();

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

    private function setContextProjects()
    {
        $viewhelper = new Viewhelper_Project_Partner($this->_user->getModel());
        $salaries   = [];
        $relations  = [];

        $partnersEntity = $viewhelper->getPartnersSeparatedByType();
        $partnersOrm    = $viewhelper->getPartners();

        foreach ($partnersOrm as $partner) {
            $salaries[$partner->project_id]     = Viewhelper_Project::getSalary(new Entity_Project($partner->project));
            $relations[$partner->project_id]    = $partner->project->getRelations();
        }

        $this->context->project_partners    = $partnersEntity;
        $this->context->salaries            = $salaries;
        $this->context->relations           = $relations;
    }
}