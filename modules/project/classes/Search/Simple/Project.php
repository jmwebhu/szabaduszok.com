<?php

/**
 * Class Search_Simple_Project
 *
 * Felelosseg: Egyszeru kereses
 */

class Search_Simple_Project extends Search_Simple
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
}