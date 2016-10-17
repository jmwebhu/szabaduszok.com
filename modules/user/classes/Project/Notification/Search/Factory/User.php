<?php

class Project_Notification_Search_Factory_User implements Search_Factory
{
    /**
     * @param array $data
     * @return Search
     */
    public static function makeSearch(array $data)
    {
        $industryIds    = Arr::get($data, 'industries', []);
        $professionIds  = Arr::get($data, 'professions', []);
        $skillIds       = Arr::get($data, 'skills', []);
        $skillRelation  = Arr::get($data, 'skill_relation', 1);

        return new Search_Complex_User($industryIds, $professionIds, $skillIds, $skillRelation);
    }
}