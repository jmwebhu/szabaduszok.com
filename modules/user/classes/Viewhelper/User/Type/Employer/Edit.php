<?php

class Viewhelper_User_Type_Employer_Edit extends Viewhelper_User_Type_Employer
{
    /**
     * @return string
     */
    public function getPageTitle()
    {
        return 'Profil szerkesztése: ';
    }

    /**
     * @return bool
     */
    public function hasPrivacyCheckbox()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getPasswordText()
    {
        return 'Legalább 6 karakter. Ha nem módosítod, hagyd üresen!';
    }

    /**
     * @return bool
     */
    public function hasIdInput()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getFormAction()
    {
        return Route::url('projectOwnerProfileEdit', ['slug' => $this->_user->getSlug()]);
    }

    /**
     * @return bool
     */
    public function hasPasswordRules()
    {
        return false;
    }
}