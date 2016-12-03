<?php

class Message_Transaction_Count_Unread extends Message_Transaction_Count
{
    /**
     * @return int
     */
    public function getCount()
    {
        return $this->baseSelect()
            ->and_where('message_interactions.is_readed', '=', 0)
            ->count_all();
    }
}