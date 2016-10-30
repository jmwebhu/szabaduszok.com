<?php

class Model_User_Employer_Mock extends Model_User_Employer
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