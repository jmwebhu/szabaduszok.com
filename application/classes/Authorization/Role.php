<?php

abstract class Authorization_Role
{
    /**
     * @var Model_User
     */
    protected $_user;

    /**
     * @var ORM
     */
    protected $_model;

    /**
     * @param Model_User $user
     * @param ORM $model
     */
    public function __construct(Model_User $user, ORM $model = null)
    {
        $this->_user    = $user;
        $this->_model   = $model;
    }
}