<?php

/**
 * Class Project_Search_Complex
 *
 * Felelosseg: Reszletes kereses
 */

class Search_Complex_Project extends Search_Complex
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