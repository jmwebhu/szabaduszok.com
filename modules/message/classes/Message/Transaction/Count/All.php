<?php

class Message_Transaction_Count_All extends Message_Transaction_Count
{
    /**
     * @return int
     */
    public function getCount()
    {
        return $this->baseSelect()->count_all();
    }
}