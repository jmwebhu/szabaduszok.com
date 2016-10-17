<?php

class Viewhelper_User
{
    const ACTION_CREATE = 'create';
    const ACTION_EDIT = 'edit';

    /**
     * @var Viewhelper_User_Type
     */
    protected $_type;

    /**
     * Viewhelper_User constructor.
     * @param Viewhelper_User_Type $_type
     */
    public function __construct(Viewhelper_User_Type $_type)
    {
        $this->_type = $_type;
    }

    /**
     * @param Entity_User $user
     * @return string
     */
    public function getEditUrl(Entity_User $user)
    {
        return $this->_type->getEditUrl($user);
    }
}