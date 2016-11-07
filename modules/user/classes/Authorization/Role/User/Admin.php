<?php

class Authorization_Role_User_Admin extends Authorization_Role_User
{
    /**
     * @return bool
     */
    public function canApply()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canUndoApplication()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canApproveApplication()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canRejectApplication()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canCancelParticipation()
    {
        return true;
    }
}