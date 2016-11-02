<?php

class Model_Project_Partner_Type_Participant extends Model_Project_Partner_Type
{
    /**
     * @return array
     */
    protected function getPerformableEventIds()
    {
        return [Model_Event::TYPE_PARTICIPATE_REMOVE, Model_Event::TYPE_PARTICIPATE_PAY];
    }
}