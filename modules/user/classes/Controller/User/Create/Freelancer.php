<?php

class Controller_User_Create_Freelancer extends Controller_User_Create
{
    /**
     * @return int
     */
    protected function getUserType()
    {
        return Entity_User::TYPE_FREELANCER;
    }

    /**
     * @return string
     */
    protected function getProfileUrl()
    {
        return Route::url('freelancerProfile', ['slug' => $this->_user->getSlug()]);
    }

    protected function setContext()
    {
        parent::setContext();

        $profile					= new Model_Profile();
        $this->context->profiles    = $profile->where('is_active', '=', 1)->find_all();
    }
}