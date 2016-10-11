<?php

class Search_Factory_User implements Search_Factory
{
    /**
     * @param array $data
     * @return Search
     */
    public static function makeSearch(array $data)
    {
        // Reszletes kereses
        if (Arr::get($data, 'complex')) {

            $industryIds    = Arr::get($data, 'industries', []);
            $professionIds  = Arr::get($data, 'professions', []);
            $skillIds       = Arr::get($data, 'skills', []);
            $skillRelation  = Arr::get($data, 'skill_relation', 1);

            $search = new Search_Complex_User($industryIds, $professionIds, $skillIds, $skillRelation);

        } else {    // Egyszeru kereses
            $search = new Search_Simple_User(Arr::get($data, 'search_term'));
        }

        return $search;
    }
}