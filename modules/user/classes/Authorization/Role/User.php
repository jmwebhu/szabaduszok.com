<?php

abstract class Authorization_Role_User extends Authorization_Role
{
    /**
     * @return bool
     */
    abstract public function canApply();

    /**
     * @return bool
     */
    abstract public function canUndoApplication();

    /**
     * @return bool
     */
    abstract public function canApproveApplication();

    /**
     * @return bool
     */
    abstract public function canRejectApplication();

    /**
     * @return bool
     */
    abstract public function canCancelParticipation();
}