<?php

class Viewhelper_User_Type_Employer extends Viewhelper_User_Type
{
    /**
     * @param Entity_User $user
     * @return string
     */
    public function getEditUrl(Entity_User $user)
    {
        return Route::url('projectOwnerProfileEdit', ['slug' => $user->getSlug()]);
    }
}