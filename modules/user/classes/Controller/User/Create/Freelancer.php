<?php

class Controller_User_Create_Freelancer extends Controller_User_Create
{
    /**
     * @return int
     */
    public function getUserType()
    {
        return Entity_User::TYPE_FREELANCER;
    }

    /**
     * @return string
     */
    public function getProfileUrl()
    {
        return Route::url('freelancerProfile', ['slug' => $this->_user->getSlug()]);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return Route::url('freelancerRegistration');
    }

    protected function setContext()
    {
        parent::setContext();

        $profile					= new Model_Profile();
        $this->context->profiles    = $profile->where('is_active', '=', 1)->find_all();
    }
}