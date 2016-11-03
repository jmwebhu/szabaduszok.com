<?php

class Viewhelper_Project_Partner_Subject_Project extends Viewhelper_Project_Partner_Subject
{
    /**
     * @return array
     */
    protected function getPartners()
    {
        return $this->_orm->partners->find_all();
    }

    /**
     * @return string
     */
    protected function getRelationEntityClass()
    {
        return Entity_User_Freelancer::class;
    }

    /**
     * @return string
     */
    protected function getRelationObjectName()
    {
        $user = new Model_User();
        return $user->object_name();
    }
}