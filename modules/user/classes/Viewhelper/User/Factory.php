<?php

abstract class Viewhelper_User_Factory
{
    public static function createViewhelper(Entity_User $user, $action)
    {
        if ($action == Viewhelper_User::ACTION_CREATE) {
            $viewhelperAction = new Viewhelper_User_Action_Create();
        }

        if ($action == Viewhelper_User::ACTION_EDIT) {
            $viewhelperAction = new Viewhelper_User_Action_Edit();
        }

        Assert::notNull($viewhelperAction);

        if ($user instanceof Entity_User_Freelancer) {
            $viewhelperType = new Viewhelper_User_Type_Freelancer($viewhelperAction);
        }

        if ($user instanceof Entity_User_Employer) {
            $viewhelperType = new Viewhelper_User_Type_Employer($viewhelperAction);
        }

        Assert::notNull($viewhelperType);

        return new Viewhelper_User($viewhelperType);
    }
}