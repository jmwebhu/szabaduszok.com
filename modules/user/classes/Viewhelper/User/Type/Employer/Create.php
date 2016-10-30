<?php

class Viewhelper_User_Type_Employer_Create extends Viewhelper_User_Type_Employer
{
    /**
     * @return string
     */
    public function getPageTitle()
    {
        return 'Megbízó Regisztráció';
    }

    /**
     * @return bool
     */
    public function hasPrivacyCheckbox()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getPasswordText()
    {
        return 'Legalább 6 karakter';
    }

    /**
     * @return bool
     */
    public function hasIdInput()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getFormAction()
    {
        return Route::url('projectOwnerRegistration');
    }

    /**
     * @return bool
     */
    public function hasPasswordRules()
    {
        return true;
    }
}