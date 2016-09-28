<?php

abstract class Project_Search_Relation
{
    protected $_project;
    protected $_searchedRelationIds = [];
    protected $_relationIdsByProjectIds = [];

    /**
     * Project_Search_Relation constructor.
     * @param $_project
     * @param array $_searchedRelationIds
     * @param array $_relationIdsByProjectIds
     */
    public function __construct($_project, array $_searchedRelationIds, array $_relationIdsByProjectIds)
    {
        $this->_project = $_project;
        $this->_searchedRelationIds = $_searchedRelationIds;
        $this->_relationIdsByProjectIds = $_relationIdsByProjectIds;
    }

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

    protected function searchOneRelationInOneProject($searchedRelationId)
    {
        $projectRelationIds = Arr::get($this->_relationIdsByProjectIds, $this->_project->project_id, []);
        return in_array($searchedRelationId, $projectRelationIds);
    }
}