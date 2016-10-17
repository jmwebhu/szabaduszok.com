<?php

class Viewhelper_User_Type_Freelancer_Create extends Viewhelper_User_Type_Freelancer
{
    /**
     * @return string
     */
    public function getPageTitle()
    {
        return 'Szabadúszó Regisztráció';
    }

    /**
     * @return bool
     */
    public function hasPrivacyCheckbox()
    {
        return true;
    }
}