<?php

class Controller_User_Create_Employer extends Controller_User_Create
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

    /**
     * @return string
     */
    public function getUrl()
    {
        return Route::url('projectOwnerRegistration');
    }
}