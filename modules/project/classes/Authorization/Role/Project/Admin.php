<?php

class Authorization_Role_Project_Admin extends Authorization_Role_Project
{
    /**
     * @return bool
     */
    public function canCreate()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canEdit()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canDelete()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function hasCancel()
    {
        return true;
    }
}