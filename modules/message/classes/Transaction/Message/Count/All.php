<?php

class Transaction_Message_Count_All extends Transaction_Message_Count
{
    /**
     * @return int
     */
    public function execute()
    {
        return $this->baseSelect()->count_all();
    }
}