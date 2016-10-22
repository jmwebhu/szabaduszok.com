<?php

class Authorization_User extends Authorization
{
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
}