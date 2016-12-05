<?php

class Transaction_Message_Count_Unread extends Transaction_Message_Count
{
    /**
     * @return int
     */
    public function execute()
    {
        return $this->baseSelect()
            ->and_where('message_interactions.is_readed', '=', 0)
            ->count_all();
    }
}