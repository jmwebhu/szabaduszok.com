<?php

class Viewhelper_Project_Edit
{
    /**
     * @return string
     */
    public static function getPageTitle()
    {
        return 'Szabadúszó projekt szerkesztése: ';
    }

    /**
     * @return bool
     */
    public static function hasIdInput()
    {
        return true;
    }

    /**
     * @param Entity_Project $project
     * @return string
     */
    public static function getFormAction(Entity_Project $project)
    {
        return Route::url('projectUpdate', ['slug' => $project->getSlug()]);
    }

    /**
     * @param Entity_Project $project
     * @param Model_User $user
     * @return string
     */
    public static function getEmail(Model_User $user, Entity_Project $project)
    {
        if ($project->getEmail()) {
            return $project->getEmail();
        }

        return $user->email;
    }

    /**
     * @param Model_User $user
     * @param Entity_Project $project
     * @return string
     */
    public static function getPhonenumber(Model_User $user, Entity_Project $project)
    {
        if ($project->getPhonenumber()) {
            return $project->getPhonenumber();
        }

        return $user->phonenumber;
    }
}