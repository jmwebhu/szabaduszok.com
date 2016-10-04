<?php

class Authorization_Project extends Authorization
{
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
        return $this->_authorization_role->canEdit();
	}
}