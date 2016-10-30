<?php

abstract class Business_User extends Business
{
    /**
     * @return string
     */
    public function getSearchTextFromFields()
    {
        $sb = SB::create($this->_model->lastname)->append(' ')->append($this->_model->firstname)->append(' ')
            ->append($this->_model->short_description)->append(' ')
            ->append(date('Y-m-d'))->append(' ')
            ->append($this->_model->address_city)->append(' ')
            ->append($this->_model->company_name)->append(' ');

        $relations = ['industries', 'professions'];

        foreach ($relations as $relation) {
            $sb->append($this->_model->getRelationString($relation))->append(' ');
        }

        return $sb->get();
    }

    /**
     * @return string
     */
    public function getLastLoginFormatted()
    {
        if (!$this->_model->last_login) {
            return 'Még nem lépett be';
        }

        return date('Y-m-d', $this->_model->last_login);
    }
}