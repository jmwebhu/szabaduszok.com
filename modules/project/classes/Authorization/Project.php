<?php

class Authorization_Project extends Authorization
{
	public function canCreate()
	{
		if ($this->before()) {
			return true;
		}

		if ($this->isUserEmployer()) {
			return true;
		}
		
		return false;
	}

	public function canEdit()
	{
		if ($this->before()) {
			return true;
		}

		if ($this->isUserOwner() && $this->isProjectActive()) {
			return true;
		}
		
		return false;
	}

	public function canDelete()
	{
		$this->canEdit();
	}

	public function hasCancel()
	{
		if ($this->before()) {
			return true;
		}

		if ($this->isUserOwner()) {
			return true;
		}
		
		return false;
	}
	
	protected function before()
	{	
		if ($this->isUserAdmin()) {
			return true;
		}
	}

	protected function isUserOwner()
    {
        return $this->_model->user_id == $this->_user->user_id;
    }

    protected function isUserAdmin()
    {
        return $this->_user->is_admin;
    }

    protected function isUserEmployer()
    {
        return $this->_user->type == 2;
    }

    protected function isProjectActive()
    {
        return $this->_model->is_active;
    }
}