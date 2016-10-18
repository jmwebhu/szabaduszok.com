<?php

abstract class Viewhelper_User_Type_Employer extends Viewhelper_User_Type
{
    /**
     * @return string
     */
    public function getEditUrl()
    {
        return Route::url('projectOwnerProfileEdit', ['slug' => $this->_user->getSlug()]);
    }

    /**
     * @param Model_Project_Notification_Relation $relationModel
     * @return array
     */
    public function getProjectNotificationRelationForProfile(Model_Project_Notification_Relation $relationModel)
    {
        return [];
    }
}