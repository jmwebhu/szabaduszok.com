<?php

/**
 * Class Search_Complex_Project
 *
 * Felelosseg: Reszletes kereses
 */

class Search_Complex_Project extends Search_Complex
{
    /**
     * @return Model_Project
     */
    public function createSearchModel()
    {
        return new Model_Project();
    }

    /**
     * @return Array_Builder
     */
    public function getInitModels()
    {
        return $this->_model->getOrderedByCreated(false);
    }

    /**
     * @return Search_Relation
     */
    protected function makeSearchRelation()
    {
        return Search_Relation_Factory_Project::makeSearch($this);
    }

    /**
     * @return string
     */
    public function getModelPrimaryKey()
    {
        return 'project_id';
    }
}