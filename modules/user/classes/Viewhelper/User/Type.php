<?php

abstract class Viewhelper_User_Type
{
    /**
     * @var Viewhelper_User_Action
     */
    protected $_action;

    /**
     * Viewhelper_User_Type constructor.
     * @param Viewhelper_User_Action $_action
     */
    public function __construct(Viewhelper_User_Action $_action)
    {
        $this->_action = $_action;
    }

    /**
     * @param Entity_User $user
     * @return string
     */
    abstract public function getEditUrl(Entity_User $user);
}