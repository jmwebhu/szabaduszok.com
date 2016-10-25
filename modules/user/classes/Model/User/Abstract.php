<?php

abstract class Model_User_Abstract extends Model_User
{
    /**
     * @return int
     */
    abstract public function getType();

    /**
     * @return int
     */
    public function getCount()
    {
        return AB::select()->from($this)->where('type', '=', $this->getType())->execute()->count();
    }
}