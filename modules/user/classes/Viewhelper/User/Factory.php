<?php

abstract class Viewhelper_User_Factory
{
    /**
     * @param Entity_User $user
     * @param string $action
     * @return Viewhelper_User
     */
    public static function createViewhelper(Entity_User $user, $action)
    {
        $viewhelperType = null;
        if ($user instanceof Entity_User_Freelancer) {
            $viewhelperType = new Viewhelper_User_Type_Freelancer_Edit();

            if ($action == Viewhelper_User::ACTION_CREATE) {
                $viewhelperType = new Viewhelper_User_Type_Freelancer_Create();
            }
        }

        if ($user instanceof Entity_User_Employer) {
            $viewhelperType = new Viewhelper_User_Type_Employer_Edit();

            if ($action == Viewhelper_User::ACTION_CREATE) {
                $viewhelperType = new Viewhelper_User_Type_Employer_Create();
            }
        }

        Assert::notNull($viewhelperType);
        $viewhelperType->setUser($user);

        return new Viewhelper_User($viewhelperType);
    }
}