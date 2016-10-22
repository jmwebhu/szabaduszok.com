<?php

abstract class Viewhelper_User_Type
{
    /**
     * @var Entity_User
     */
    protected $_user = null;

    /**
     * @param Entity_User $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

    /**
     * @return string
     */
    abstract public function getEditUrl();

    /**
     * @return string
     */
    abstract public function getPageTitle();

    /**
     * @return bool
     */
    abstract public function hasPrivacyCheckbox();

    /**
     * @return string
     */
    abstract public function getPasswordText();

    /**
     * @return bool
     */
    abstract public function hasIdInput();

    /**
     * @return string
     */
    abstract public function getFormAction();

    /**
     * @return bool
     */
    abstract public function hasPasswordRules();

    /**
     * @param Model_Project_Notification_Relation $relationModel
     * @return array
     */
    abstract public function getProjectNotificationRelationForProfile(Model_Project_Notification_Relation $relationModel);

    /**
     * @return bool
     */
    abstract public function hasCv();

    /**
     * @return bool
     */
    public function hasPicture()
    {
        if ($this->_user->loaded() && $this->_user->getProfilePicturePath()) {
            return true;
        }

        return false;
    }


}