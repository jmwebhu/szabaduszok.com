<?php

abstract class Authorization_Role_Project_Factory
{
    /**
     * @param Authorization_Project $authorization
     * @return Authorization_Role_Project
     */
    public static function makeRole(Authorization_Project $authorization)
    {
        if ($authorization->getUser()->is_admin) {
            return new Authorization_Role_Project_Admin($authorization->getUser(), $authorization->getModel());
        }

        if ($authorization->getUser()->type == 2) {
            return new Authorization_Role_Project_Employer($authorization->getUser(), $authorization->getModel());
        }

        return new Authorization_Role_Project_Freelancer($authorization->getUser(), $authorization->getModel());
    }
}