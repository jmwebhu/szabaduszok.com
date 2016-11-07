<?php

class Authorization_Role_User_Freelancer extends Authorization_Role_User
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
        return false;
    }

    /**
     * @return bool
     */
    public function canRejectApplication()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canCancelParticipation()
    {
        return false;
    }
}