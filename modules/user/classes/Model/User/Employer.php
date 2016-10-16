<?php

class Model_User_Employer extends Model_User_Abstract
{
    /**
     * @return int
     */
    public function getType()
    {
        return Entity_User::TYPE_EMPLOYER;
    }

    /**
     * @return Array_Builder
     */
    public function baseSelect()
    {
        $base = parent::baseSelect();
        return $base->where('type', '=', $this->getType());
    }
}