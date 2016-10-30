<?php

class Model_User_Freelancer_Mock extends Model_User_Freelancer
{
    /**
     * @param string $name
     * @return string
     */
    public function getRelationString($name)
    {
        return $name;
    }
}