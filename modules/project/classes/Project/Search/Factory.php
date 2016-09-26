<?php

class Project_Search_Factory
{
    /**
     * Peldanyositja a megfelelo Strategy -t
     *
     * @param array $data               POST adatok, tartalmazza a kereses tipusat
     * @return Project_Search           A megfelelo Project_Search alosztaly egy peldanya
     */
    public static function getAndSetSearch(array $data, Entity_Project $project)
    {
        // Reszletes kereses
        if (Arr::get($data, 'complex'))
        {
            $search = new Project_Search_Complex();
        }
        else 	// Egyszeru kereses
        {
            $search = new Project_Search_Simple();
        }

        $project->setSearch($search);

        return $search;
    }
}