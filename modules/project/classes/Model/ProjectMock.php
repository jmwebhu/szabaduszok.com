<?php

/**
 * class Model_ProjectMock
 *
 * Model_Project -hez tartozo Mock osztaly unittestek reszere
 */

class Model_ProjectMock extends Model_Project
{
    public function getRelationString($name)
    {
    	return $name;
    }
}
