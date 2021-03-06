<?php

class Controller_User_Update_Freelancer extends Controller_User_Update
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
        return Route::url('freelancerProfileEdit', ['slug' => $this->_user->getSlug()]);
    }

    protected function setContext()
    {
        parent::setContext();

        $viewhelper  = Viewhelper_User_Factory::createViewhelper($this->_user, Viewhelper_User::ACTION_EDIT);
        $this->context->hasCv       	= $viewhelper->hasCv();
        $profile						= new Model_Profile();
        $this->context->profiles		= $profile->where('is_active', '=', 1)->find_all();
        $this->context->userProfileUrls	= $this->_user->getProfileUrls(new Model_User_Profile());

        if ($this->context->hasCv) {
            $parts                  = explode('.', $this->_user->getCvPath());
            $this->context->cvExt   = $parts[count($parts) - 1];
        }
    }
}