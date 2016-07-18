<?php

class Authorization
{
	protected $_model;
	protected $_user;
	
	public function __construct(ORM $model = null, Model_User $user = null)
	{
		$this->_model = $model;		
		$this->_user = ($user) ? $user : Auth::instance()->get_user();
	}
}