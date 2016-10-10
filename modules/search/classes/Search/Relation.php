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
     * @var Search_Complex
     */
    protected $_complexSearch;

    /**
     * @param Search_Complex $complex
     */
    public function __construct(Search_Complex $complex)
    {
        $this->_complexSearch = $complex;
    }

    /**
     * @return bool
     */
    public function searchRelationsInOneModel()
    {
        foreach ($this->_complexSearch->getSearchedRelationIds() as $searchedRelationId) {
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
        $model = $this->_complexSearch->getCurrentModel();
        $modelRelationIds = Arr::get(
            $this->_complexSearch->getRelationIdsByModelIds(),
            $model->{$this->_complexSearch->getModelPrimaryKey()}, []);

        return in_array($searchedRelationId, $modelRelationIds);
    }
}