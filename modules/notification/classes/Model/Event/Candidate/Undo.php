<?php

class Model_Event_Candidate_Undo extends Model_Event
{
    /**
     * @return string
     */
    public function getNotifierClass()
    {
        return Entity_User_Freelancer::class;
    }

    /**
     * @return string
     */
    public function getNotifiedClass()
    {
        return Entity_User_Employer::class;
    }
}