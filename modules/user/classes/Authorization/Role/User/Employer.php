<?php

class Authorization_Role_User_Employer extends Authorization_Role_User
{
    /**
     * @return bool
     */
    public function canApply()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canUndoApplication()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canApproveApplication()
    {
        return ($this->_user->user_id == $this->_model->user_id);
    }

    /**
     * @return bool
     */
    public function canRejectApplication()
    {
        return ($this->_user->user_id == $this->_model->user_id);
    }

    /**
     * @return bool
     */
    public function canCancelParticipation()
    {
        return ($this->_user->user_id == $this->_model->user_id);
    }
}