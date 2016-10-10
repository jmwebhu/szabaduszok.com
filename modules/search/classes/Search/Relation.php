<?php

/**
 * Class Search_Relation
 *
 * Projekt relaciok keresesere szolgalo Strategy alaposztaly.
 * A konkret peldanyok ebbol orokolnek, vannak akik nem irjak felul az alapertelmezett viselkedest,
 * ezert van interface helyett abstract osztaly, megvalositott metodusokkal.
 *
 * @author Joo Martin
 * @since 2.2
 * @version 1.0
 */

abstract class Search_Relation
{
    /**
     * @var Model_Project
     */
    protected $_project;
    protected $_searchedRelationIds     = [];
    protected $_relationIdsByProjectIds = [];

    /**
     * @param Model_Project $project
     * @param array $searchedRelationIds
     * @param array $relationIdsByProjectIds
     */
    public function __construct(Model_Project $project, array $searchedRelationIds, array $relationIdsByProjectIds)
    {
        $this->_project                 = $project;
        $this->_searchedRelationIds     = $searchedRelationIds;
        $this->_relationIdsByProjectIds = $relationIdsByProjectIds;
    }

    /**
     * @return bool
     */
    public function searchRelationsInOneProject()
    {
        foreach ($this->_searchedRelationIds as $searchedRelationId) {
            $found = $this->searchOneRelationInOneProject($searchedRelationId);

            if ($found) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $searchedRelationId
     * @return bool
     */
    protected function searchOneRelationInOneProject($searchedRelationId)
    {
        $projectRelationIds = Arr::get($this->_relationIdsByProjectIds, $this->_project->project_id, []);
        return in_array($searchedRelationId, $projectRelationIds);
    }
}