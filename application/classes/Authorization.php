<?php

class Authorization
{
    /**
     * @var ORM
     */
	protected $_model;
    /**
     * @var Model_User
     */
	protected $_user;

    /**
     * @var Authorization_Role
     */
    protected $_authorization_role;
	
	public function __construct(ORM $model = null, Model_User $user = null)
	{
		$this->_model   = $model;
		$this->_user    = ($user) ? $user : Auth::instance()->get_user();
	}

	public function setRole(Authorization_Role $role)
    {
        $this->_authorization_role = $role;
    }

    /**
     * @return Model_User
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @return ORM
     */
    public function getModel()
    {
        return $this->_model;
    }
}