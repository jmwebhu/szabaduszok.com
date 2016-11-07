<?php

class Authorization_Role_User_Freelancer extends Authorization_Role_User
{
    public function __construct(Model_User $user, ORM $model)
    {
        $this->_user = $user;

        // FONTOS, unitteszt dependency injection miatt kell sajnos. testCanApplyNotOk()
        // A konstruktornak atadott mock objectet felulirja a createUser egy rendes user objecttel
        if (!$user instanceof Model_User_Freelancer) {
            $this->_user    = Model_User::createUser(Entity_User::TYPE_FREELANCER, $user->user_id);
        }

        $this->_model   = $model;
    }

    /**
     * @return bool
     */
    public function canApply()
    {
        return (!$this->_user->isCandidateIn($this->_model) && !$this->_user->isParticipantIn($this->_model));
    }

    /**
     * @return bool
     */
    public function canUndoApplication()
    {
        return ($this->_user->isCandidateIn($this->_model) && !$this->_user->isParticipantIn($this->_model));
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