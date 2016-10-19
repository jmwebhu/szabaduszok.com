<?php

class Controller_User_Update_Employer extends Controller_User_Update
{
    /**
     * @return int
     */
    public function getUserType()
    {
        return Entity_User::TYPE_EMPLOYER;
    }

    /**
     * @return string
     */
    public function getProfileUrl()
    {
        return Route::url('projectOwnerProfile', ['slug' => $this->_user->getSlug()]);
    }
}