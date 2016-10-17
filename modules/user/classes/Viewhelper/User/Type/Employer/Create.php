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
}