<?php

/**
 * Class Authorization_Project
 *
 * Felelosseg: projekt hozzaferes szabalyozasa
 */

class Authorization_Project extends Authorization
{
<<<<<<< HEAD
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
=======
    /**
     * @param ORM|null $model
     * @param Model_User|null $user
     */
    public function __construct(ORM $model = null, Model_User $user = null)
    {
        parent::__construct($model, $user);
        $this->_authorization_role = Authorization_Role_Project_Factory::makeRole($this);
    }

    /**
     * @return bool
     */
>>>>>>> project_refact
	public function canCreate()
	{
		return $this->_authorization_role->canCreate();
	}

    /**
     * @return bool
     */
	public function canEdit()
	{
		return $this->_authorization_role->canEdit();
	}

    /**
     * @return boolean
     */
	public function canDelete()
	{
        return $this->_authorization_role->canDelete();
	}

    /**
     * @return bool
     */
	public function hasCancel()
	{
<<<<<<< HEAD
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
=======
        return $this->_authorization_role->canEdit();
>>>>>>> project_refact
	}
}