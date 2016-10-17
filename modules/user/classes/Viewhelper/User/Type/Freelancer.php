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
}