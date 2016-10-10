<?php

/**
 * Class Search_Relation
 *
 * @author Joo Martin
 * @since 2.2
 * @version 1.0
 */

abstract class Search_Relation
{
    /**
     * @var ORM
     */
    protected $_model;
    protected $_searchedRelationIds     = [];
    protected $_relationIdsByModelIds   = [];

    /**
     * @param ORM $model
     * @param array $searchedRelationIds
     * @param array $relationIdsByModelIds
     */
    public function __construct(ORM $model, array $searchedRelationIds, array $relationIdsByModelIds)
    {
        $this->_model                   = $model;
        $this->_searchedRelationIds     = $searchedRelationIds;
        $this->_relationIdsByModelIds   = $relationIdsByModelIds;
    }

    /**
     * @return bool
     */
    public function searchRelationsInOneModel()
    {
        foreach ($this->_searchedRelationIds as $searchedRelationId) {
            $found = $this->searchOneRelationInOneModel($searchedRelationId);

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
    protected function searchOneRelationInOneModel($searchedRelationId)
    {
        $modelRelationIds = Arr::get($this->_relationIdsByModelIds, $this->_model->pk(), []);
        return in_array($searchedRelationId, $modelRelationIds);
    }
}