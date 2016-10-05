<?php

/**
 * Class Authorization_Role
 *
 * @todo nem biztos, hogy szukseg lesz az osztalyra, ez majd a user modul refactolasakor derul ki
 */

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