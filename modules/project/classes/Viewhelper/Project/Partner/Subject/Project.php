<?php

class Viewhelper_Project_Partner_Subject_Project extends Viewhelper_Project_Partner_Subject
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
        return $this->_orm->partners->find_all();
    }
}