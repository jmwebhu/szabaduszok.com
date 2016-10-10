<?php

/**
 * Class Search_Simple_Project
 *
 * Felelosseg: Egyszeru kereses
 */

class Search_Simple_Project extends Search_Simple
{
    /**
     * @return ORM
     */
    public function createSearchModel()
    {
        return new ORM();
    }

    /**
     * @return Array_Builder
     */
    public function getInitModels()
    {
        return $this->_model->getOrderedByCreated(false);
    }
}