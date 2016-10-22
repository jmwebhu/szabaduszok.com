<?php

abstract class Viewhelper_User_Type_Freelancer extends Viewhelper_User_Type
{
    /**
     * @return string
     */
    public function getEditUrl()
    {
        return Route::url('freelancerProfileEdit', ['slug' => $this->_user->getSlug()]);
    }

    /**
     * @param Model_Project_Notification_Relation $relationModel
     * @return array
     */
    public function getProjectNotificationRelationForProfile(Model_Project_Notification_Relation $relationModel)
    {
        $relations      = $relationModel->getAll();
        $notifications  = $this->_user->getRelation($relationModel->getUserProjectNotificationRelationName());

        $ids            = [];
        $result         = [];

        foreach ($notifications as $notification) {
            $ids[] = $notification->{$relationModel->primary_key()};
        }

        foreach ($relations as $relation) {
            $result[] = [
                'id'		=> $relation->{$relationModel->primary_key()},
                'name'		=> $relation->name,
                'selected'	=> (in_array($relation->{$relationModel->primary_key()}, $ids)) ? 'selected' : '',
            ];
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function hasCv()
    {
        if ($this->_user->loaded() && $this->_user->getCvPath()) {
            return true;
        }

        return false;
    }
}