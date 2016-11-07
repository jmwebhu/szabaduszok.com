<?php

class Model_Project_Partner_Type_Candidate extends Model_Project_Partner_Type
{
    /**
     * @return array
     */
    protected function getPerformableEventIds()
    {
        return [Model_Event::TYPE_CANDIDATE_NEW, Model_Event::TYPE_CANDIDATE_UNDO, Model_Event::TYPE_CANDIDATE_ACCEPT, Model_Event::TYPE_CANDIDATE_REJECT];
    }

    /**
     * @return string
     */
    public function getTypePlural()
    {
        return self::TYPE_CANDIDATES;
    }
}