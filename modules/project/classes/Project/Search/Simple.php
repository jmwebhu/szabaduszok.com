<?php

/**
 * Class Project_Search_Simple
 *
 * Felelosseg: Egyszeru kereses
 */

class Project_Search_Simple implements Project_Search
{
    /**
     * @var string
     */
    private $_searchTerm;

    /**
     * @var Model_Project
     */
    private $_project;

    /**
     * Project_Search_Simple constructor.
     * @param string $searchTerm
     */
    public function __construct($searchTerm)
    {
        $this->_searchTerm  = $searchTerm;
        $this->_project     = new Model_Project();
    }

    /**
     * @return array
     */
    public function search()
    {
        /**
         * @var $projects Array_Builder
         */
        $projects = $this->_project->getOrderedByCreated(false);

        if (!$this->_searchTerm) {
            return $projects->execute()->as_array();
        }

        return $projects->and_where('search_text', 'LIKE', $this->_searchTerm)->execute()->as_array();
    }
}