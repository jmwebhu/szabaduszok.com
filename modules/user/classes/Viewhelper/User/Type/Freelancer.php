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
     * @param ORM $relationModel
     * @return array
     */
    public function getProjectNotificationRelationForProfile(ORM $relationModel)
    {
        $relations      = $relationModel->getAll();
        $notifications  = $this->_user->project_notification_skills->find_all();
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
}