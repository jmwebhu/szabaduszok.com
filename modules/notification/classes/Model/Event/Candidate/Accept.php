<?php

class Model_Event_Candidate_Accept extends Model_Event
{
    /**
     * @return string
     */
    public function getNotifierClass()
    {
        return Entity_User_Employer::class;
    }

    /**
     * @return string
     */
    public function getNotifiedClass()
    {
        return Entity_User_Freelancer::class;
    }
}