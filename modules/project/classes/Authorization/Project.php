<?php

class Authorization_Project extends Authorization
{
	/**
	 * @todo
	 */
	public function __call($method, $arguments) 
	{
        /*if (method_exists($this, $method))
        {
            $before = $this->before();

            if ($before)
            {
            	return true;
            }

            return call_user_func_array([$this, $method], $arguments);
        }*/
    }

	/**
	 * Lathatja -e a felvitel oldalt
	 */
	public function canCreate()
	{
		if ($this->before())
		{
			return true;
		}
		
		// Csak Megbizo
		if ($this->_user->type == 2)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Van -e szerkesztes
	 */
	public function canEdit()
	{
		if ($this->before())
		{
			return true;
		}
		
		// Sajat projektje
		if ($this->_model->user_id == $this->_user->user_id && $this->_model->is_active == 1)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Van -e torles gomb 
	 */
	public function canDelete()
	{
		if ($this->before())
		{
			return true;
		}
		
		// Sajat projektje es aktiv
		if ($this->_model->user_id == $this->_user->user_id && $this->_model->is_active == 1)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Van -e megsem gomb
	 */
	public function hasCancel()
	{
		if ($this->before())
		{
			return true;
		}
		
		// Sajat projektje
		if ($this->_model->user_id == $this->_user->user_id)
		{
			return true;
		}
		
		return false;
	}
	
	protected function before()
	{	
		if ($this->_user->is_admin == 1)
		{
			return true;
		}
	}
}