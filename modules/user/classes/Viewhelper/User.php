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

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->_type->getPageTitle();
    }

    /**
     * @return bool
     */
    public function hasPrivacyCheckbox()
    {
        return $this->_type->hasPrivacyCheckbox();
    }

    /**
     * @return string
     */
    public function getPasswordText()
    {
        return $this->_type->getPasswordText();
    }

    /**
     * @return bool
     */
    public function hasIdInput()
    {
        return $this->_type->hasIdInput();
    }

    /**
     * @return string
     */
    public function getFormAction()
    {
        return $this->_type->getFormAction();
    }

    /**
     * @return bool
     */
    public function hasPasswordRules()
    {
        return $this->_type->hasPasswordRules();
    }
}