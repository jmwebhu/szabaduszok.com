<?php

class Business_User_Freelancer extends Business_User
{
    /**
     * @return string
     */
    public function getSearchTextFromFields()
    {
        $sb = SB::create(parent::getSearchTextFromFields());
        $sb->append($this->_model->getRelationString('skills'));

        return $sb->get();
    }
}