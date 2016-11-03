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
        $candidates     = $this->_user->getModel()->project_partners->where('type', '=', Model_Project_Partner::TYPE_CANDIDATE)->find_all();
        $participants   = $this->_user->getModel()->project_partners->where('type', '=', Model_Project_Partner::TYPE_PARTICIPANT)->find_all();

        //$partnersEntity     = ['candidates' => [], 'participants' => []];
        $salaries           = [];
        $relations          = [];

        $partnersEntity = $viewhelper->getPartnersSeparatedByType();

        foreach ($candidates as $i => $candidate) {
            $partnersEntity['candidates'][$i]                       = [];
            $partnersEntity['candidates'][$i]['project']            = new Entity_Project($candidate->project);
            $partnersEntity['candidates'][$i]['project_partner']    = $candidate;
        }

        foreach ($participants as $i => $participant) {
            $partnersEntity['participants'][$i][]                   = [];
            $partnersEntity['participants'][$i]['project']          = new Entity_Project($participant->project);
            $partnersEntity['participants'][$i]['project_partner']  = $participant;
        }

        foreach ($partnersEntity['candidates'] as $partner) {
            $salaries[$partner['project']->getProjectId()]          = Viewhelper_Project::getSalary($partner['project']);
        }

        foreach ($partnersEntity['participants'] as $partner) {
            $salaries[$partner['project']->getProjectId()]          = Viewhelper_Project::getSalary($partner['project']);
        }

        foreach ($partnersEntity['candidates'] as $partner) {
            $relations[$partner['project']->getProjectId()]         = $partner['project']->getModel()->getRelations();
        }

        foreach ($partnersEntity['participants'] as $partner) {
            $relations[$partner['project']->getProjectId()]         = $partner['project']->getModel()->getRelations();
        }

        $this->context->project_partners    = $partnersEntity;
        $this->context->salaries            = $salaries;
        $this->context->relations           = $relations;
    }
}