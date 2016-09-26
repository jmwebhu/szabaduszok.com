<?php

class Project_Search_Simple implements Project_Search
{
    /**
     * Egyszeru szabadszavad kereses adott kulcsszora.
     *
     * @param array $data               Keresett adatok benne egy 'search_term' index
     * @param Model_Project $project    Ures project
     *
     * @return array                    Talalatok
     */
    public function search(array $data, Model_Project $project)
    {
        $searchTerm 	= Arr::get($data, 'search_term');

        /**
         * @var $projects Array_Builder
         */
        $projects = AB::select()->from('projects')->where('is_active', '=', 1)->order_by('created_at', 'DESC');
        if (!$searchTerm)
        {
            return $projects->execute()->as_array();
        }

        return $projects->and_where('search_text', 'LIKE', $searchTerm)->execute()->as_array();
    }
}