<?php

class Authorization_Role_User_Factory
{
    /**
     * @param Authorization_User $authorization
     * @return Authorization_Role_User
     */
    public static function createRole(Authorization_User $authorization)
    {
        if ($authorization->getUser()->is_admin) {
            return new Authorization_Role_User_Admin($authorization->getUser(), $authorization->getModel());
        }

        if ($authorization->getUser()->type == Entity_User::TYPE_EMPLOYER) {
            return new Authorization_Role_User_Employer($authorization->getUser(), $authorization->getModel());
        }

        return new Authorization_Role_User_Freelancer($authorization->getUser(), $authorization->getModel());
    }
}