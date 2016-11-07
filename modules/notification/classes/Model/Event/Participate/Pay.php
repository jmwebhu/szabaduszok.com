<?php

class Model_Event_Participate_Pay extends Model_Event
{
    /**
     * @return array
     */
    public function getData()
    {
        /**
         * @todo
         */
        return [];
    }

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