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
        return DB::select([DB::expr('COUNT(user_id)'), 'total_record'])->from($this->_table_name)->where('type', '=', $this->getType())->execute()->get('total_record');
        //return AB::select()->from($this)->where('type', '=', $this->getType())->execute()->count();
    }
}