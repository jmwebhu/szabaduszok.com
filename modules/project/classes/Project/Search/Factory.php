<?php

class Project_Search_Factory
{
    /**
     * Peldanyositja a megfelelo Strategy -t
     *
     * @param array $data               POST adatok, tartalmazza a kereses tipusat
     * @param mixed $project            Entity_Project peldany. Ha van, akkor be allitja a letrehozott search peldanyt
     * @return Project_Search           A megfelelo Project_Search alosztaly egy peldanya
     */
    public static function getAndSetSearch(array $data, Entity_Project $project = null)
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

        if ($project)
        {
            $project->setSearch($search);
        }

        return $search;
    }
}