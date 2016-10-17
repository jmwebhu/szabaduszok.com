<?php

abstract class Viewhelper_User_Type_Employer extends Viewhelper_User_Type
{
    /**
     * @return string
     */
    public function getEditUrl()
    {
        return Route::url('projectOwnerProfileEdit', ['slug' => $this->_user->getSlug()]);
    }
}