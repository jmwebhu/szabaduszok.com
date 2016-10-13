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
}