<?php

class Viewhelper_Project_Partner_Subject_User extends Viewhelper_Project_Partner_Subject
{
    /**
     * @return array
     */
    public function getPartnersSeparatedByType()
    {
        // TODO: Implement getPartnersSeparatedByType() method.
    }

    /**
     * @return array
     */
    protected function getPartners()
    {
        return $this->_orm->project_partners->find_all();
    }
}