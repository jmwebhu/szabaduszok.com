<?php

class Viewhelper_User_Type_Freelancer_Edit extends Viewhelper_User_Type_Freelancer
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