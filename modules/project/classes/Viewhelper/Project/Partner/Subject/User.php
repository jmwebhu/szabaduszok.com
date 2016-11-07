<?php

class Viewhelper_Project_Partner_Subject_User extends Viewhelper_Project_Partner_Subject
{
    /**
     * @return array
     */
    public function getPartners()
    {
        return $this->_orm->project_partners->find_all();
    }

    /**
     * @return string
     */
    protected function getRelationEntityClass()
    {
        return Entity_Project::class;
    }

    /**
     * @return string
     */
    protected function getRelationObjectName()
    {
        $project = new Model_Project();
        return $project->object_name();
    }
}