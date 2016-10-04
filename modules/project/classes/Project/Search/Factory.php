<?php

class Project_Search_Factory
{
    /**
     * @param array $data
     * @return Project_Search
     */
    public static function makeSearch(array $data)
    {
        // Reszletes kereses
        if (Arr::get($data, 'complex')) {

            $industryIds    = Arr::get($data, 'industries', []);
            $professionIds  = Arr::get($data, 'professions', []);
            $skillIds       = Arr::get($data, 'skills', []);
            $skillRelation  = Arr::get($data, 'skill_relation', 1);

            $search = new Project_Search_Complex($industryIds, $professionIds, $skillIds, $skillRelation);

        } else {    // Egyszeru kereses
            $search = new Project_Search_Simple(Arr::get($data, 'search_term'));
        }

        return $search;
    }
}