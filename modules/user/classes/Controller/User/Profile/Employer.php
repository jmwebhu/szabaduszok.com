<?php

class Controller_User_Profile_Employer extends Controller_User_Profile
{
    /**
     * @return int
     */
    public function getUserType()
    {
        return Entity_User::TYPE_EMPLOYER;
    }

    /**
     * @return Model_User_Employer
     */
    public function getUserModel()
    {
        return new Model_User_Employer();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Megbízó profil';
    }

    protected function setContext()
    {
        parent::setContext();
        $this->setContextRelations();
    }

    private function setContextRelations()
    {
        $industry	= new Model_Industry();
        $projects 	= $this->_user->getProjects();
        $relations	= [];
        $salaries	= [];
        $users 		= [];

        foreach ($projects as $pr)
        {
            /**
             * @var $pr Model_Project
             */
            $relations[$pr->project_id]	= $pr->getRelations();
            $salaries[$pr->project_id]	= Viewhelper_Project::getSalary(new Entity_Project($pr->project_id));
            $users[$pr->project_id]		= $this->_user;
        }

        $entityProject              = new Entity_Project();
        $this->context->projects 	= $entityProject->getEntitiesFromModels($projects);
        $this->context->relations	= $relations;
        $this->context->salaries	= $salaries;
        $this->context->users		= $users;
        $this->context->industries	= $industry->getAll();
    }
}