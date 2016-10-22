<?php

class Authorization_User extends Authorization
{
	/**
     * @param Auth $auth Unittest dependency injection
	 * @return bool
	 */
	public function canRate($auth = null)
	{
	    $auth = ($auth) ? $auth : Auth::instance();
		if ($this->_model->type == $this->_user->type || $this->_model->user_id == $this->_user->user_id
            || !$auth->logged_in() || $this->_model->has('ratings', $this->_user)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * @return bool
	 */
	public function canEdit()
	{
		if ($this->_model->user_id == $this->_user->user_id) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * @return bool
	 */
	public function canSeeProjectNotification()
	{
		if ($this->_model->user_id == $this->_user->user_id) {
			return true;
		}
		
		return false;
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