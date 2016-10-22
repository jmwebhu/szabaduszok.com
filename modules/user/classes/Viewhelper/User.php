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
     * @return Viewhelper_User_Type
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return string
     */
    public function getEditUrl()
    {
        return $this->_type->getEditUrl();
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

    /**
     * @return bool
     */
    public function hasPicture()
    {
        return $this->_type->hasPicture();
    }

    /**
     * @return bool
     */
    public function hasCv()
    {
        return $this->_type->hasCv();
    }

    /**
     * @param Model_Project_Notification_Relation $relationModel
     * @return array
     */
    public function getProjectNotificationRelationForProfile(Model_Project_Notification_Relation $relationModel)
    {
        return $this->_type->getProjectNotificationRelationForProfile($relationModel);
    }
}