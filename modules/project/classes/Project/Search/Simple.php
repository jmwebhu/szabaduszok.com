<?php

class Project_Search_Simple implements Project_Search
{
    private $_searchTerm;
    private $_project;

    /**
     * Project_Search_Simple constructor.
     * @param $searchTerm
     */
    public function __construct($searchTerm)
    {
        $this->_searchTerm  = $searchTerm;
        $this->_project     = new Model_Project();
    }

    public function search()
    {
        /**
         * @var $projects Array_Builder
         */
        $projects = $this->_project->getActivesOrderedByCreated(false);

        if (!$this->_searchTerm) {
            return $projects->execute()->as_array();
        }

        return $projects->and_where('search_text', 'LIKE', $this->_searchTerm)->execute()->as_array();
    }
}