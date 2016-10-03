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
	
	public function __construct(ORM $model = null, Model_User $user = null)
	{
		$this->_model   = $model;
		$this->_user    = ($user) ? $user : Auth::instance()->get_user();
	}
}