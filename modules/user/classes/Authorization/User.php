<?php

class Authorization_User extends Authorization
{
    /**
     * @param ORM|null $model
     * @param Model_User|null $user
     */
    public function __construct(ORM $model = null, Model_User $user = null)
    {
        parent::__construct($model, $user);
        $this->_authorization_role = Authorization_Role_User_Factory::createRole($this);
    }

	/**
     * @param Auth $auth Unittest dependency injection
	 * @return bool
	 */
	public function canRate($auth = null)
	{
	    if ($auth == null) {
	        $auth = Auth::instance();
            }

            return !($this->_model->type == $this->_user->type || $this->_model->user_id == $this->_user->user_id
                || !$auth->logged_in() || $this->_model->has('ratings', $this->_user));
	}
	
	/**
	 * @return bool
	 */
	public function canEdit()
	{
		return $this->_model->user_id == $this->_user->user_id;
	}
	
	/**
	 * @return bool
	 */
	public function canSeeProjectNotification()
	{
		return $this->_model->user_id == $this->_user->user_id;
	}
	
	/**
	 * return bool
	 */
	public function hasCancel()
	{
		return $this->_model->loaded();
	}
	
	/**
	 * Lathatja -e a szabaduszo listat
	 */
	public function canSeeFreelancers()
	{
	    $user = Auth::instance()->get_user();
		return $user->loaded();
	}

    /**
     * @return bool
     */
	public function canApply()
    {
        return $this->_authorization_role->canApply();
    }

    /**
     * @return bool
     */
    public function canUndoApplication()
    {
        return $this->_authorization_role->canUndoApplication();
    }

    /**
     * @return bool
     */
    public function canApproveApplication()
    {
        return $this->_authorization_role->canApproveApplication();
    }

    /**
     * @return bool
     */
    public function canRejectApplication()
    {
        return $this->_authorization_role->canRejectApplication();
    }

    /**
     * @return bool
     */
    public function canCancelParticipation()
    {
        return $this->_authorization_role->canCancelParticipation();
    }
}
