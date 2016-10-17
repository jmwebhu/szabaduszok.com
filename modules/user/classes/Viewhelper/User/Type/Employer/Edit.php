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
}