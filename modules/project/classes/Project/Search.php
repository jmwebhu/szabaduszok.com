<?php

interface Project_Search
{
    /**
     * Kereses
     *
     * @param array $data               Keresett adatok
     * @param Model_Project $project    Ures project
     *
     * @return array                    Talalatok
     */
    public function search();
}