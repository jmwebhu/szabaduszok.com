<?php

/**
 * Class Authorization_Role_Project_Employer
 *
 * Felelosseg: Megbizo tipusu felhasznalo hozzaferesenek szabalyozasa
 */

class Authorization_Role_Project_Employer extends Authorization_Role_Project
{
    /**
     * @return bool
     */
    public function canCreate()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canEdit()
    {
        if ($this->isUserOwner() && $this->isProjectActive()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canDelete()
    {
        return $this->canEdit();
    }

    /**
     * @return bool
     */
    public function hasCancel()
    {
        if ($this->isUserOwner()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isUserOwner()
    {
        return $this->_model->user_id == $this->_user->user_id;
    }

    /**
     * @return mixed
     */
    protected function isProjectActive()
    {
        return $this->_model->is_active;
    }

}