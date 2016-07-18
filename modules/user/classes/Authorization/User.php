<?php

class Authorization_User extends Authorization
{
	/**
	 * ERtekelheti -e a felhasznalot
	 * 
	 * @return boolean
	 */
	public function canRate()
	{					
		// Szabaduszo szabaduszot, vagy pt pt -t nem ertekelhet
		if ($this->_model->type == $this->_user->type)
		{
			return false;
		}
		
		// Onmagat nem ertekelheti
		if ($this->_model->user_id == $this->_user->user_id)
		{
			return false;
		}				
		
		// Kulso felhasznalo nem ertkelhet
		if (!Auth::instance()->logged_in())
		{
			return false;
		}
		
		// Mar ertekelte
		if ($this->alreadyHasRating())
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * Ertekelte -e mar a felhasznalot
	 * 
	 * @return boolean
	 */
	public function alreadyHasRating()
	{
		// Mar ertekelte
		if ($this->_model->has('ratings', $this->_user))
		{
			return true;
		}	
		
		return false;
	}
	
	/**
	 * Szerkesztheti -e a profilt
	 * 
	 * @return boolean
	 */
	public function canEdit()
	{
		// Sajat maga
		if ($this->_model->user_id == $this->_user->user_id)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Lathatja -e a projekt ertesito beallitasait
	 * 
	 * @return boolean
	 */
	public function canSeeProjectNotification()
	{
		// Sajat maga	
		if ($this->_model->user_id == $this->_user->user_id)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Van -e 'Megsem' gomb
	 */
	public function hasCancel()
	{
		// Ha van felhasznalo
		if ($this->_model->loaded())
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Lathatja -e a szabaduszo listat
	 */
	public function canSeeFreelancers()
	{
		if (Auth::instance()->get_user())
		{
			return true;
		}
		
		return false;
	}
}