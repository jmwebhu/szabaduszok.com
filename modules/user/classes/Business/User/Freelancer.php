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
        $sb->append($this->getAbleToBillString());

        return $sb->get();
    }

    protected function getAbleToBillString()
    {
        return ($this->_model->is_able_to_bill) ? ' Számlaképes' : '';
    }
}