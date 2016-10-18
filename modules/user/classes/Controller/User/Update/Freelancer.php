<?php

class Controller_User_Update_Freelancer extends Controller_User_Update
{
    protected function setContext()
    {
        parent::setContext();

        $this->context->hasCv       	= $this->_viewhelper->hasCv();
        $profile						= new Model_Profile();
        $this->context->profiles		= $profile->where('is_active', '=', 1)->find_all();
        $this->context->userProfileUrls	= $this->_user->getProfileUrls(new Model_User_Profile());

        if ($this->context->hasCv) {
            $parts                  = explode('.', $this->_user->getCvPath());
            $this->context->cvExt   = $parts[count($parts) - 1];
        }
    }

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
}