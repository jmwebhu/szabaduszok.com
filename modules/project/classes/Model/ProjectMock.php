<?php

/**
 * class Model_ProjectMock
 *
 * Model_Project -hez tartozo Mock osztaly unittestek reszere
 */

class ORMMock extends ORM
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
