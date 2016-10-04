<?php

class Viewhelper_Project_Create
{
    /**
     * @return string
     */
    public static function getPageTitle()
    {
        return 'Új Szabadúszó projekt';
    }

    /**
     * @return bool
     */
    public static function hasIdInput()
    {
        return false;
    }

    /**
     * @return string
     */
    public static function getFormAction()
    {
        return Route::url('projectCreate');
    }

    /**
     * @param Model_User $user
     * @return string
     */
    public static function getEmail(Model_User $user)
    {
        return $user->email;
    }

    /**
     * @param Model_User $user
     * @return string
     */
    public static function getPhonenumber(Model_User $user)
    {
        return $user->phonenumber;
    }
}