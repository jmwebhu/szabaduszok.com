<?php

/**
 * Class Search_Simple
 *
 * Felelosseg: Egyszeru kereses
 */

abstract class Search_Simple implements Search
{
    /**
     * Be kell allitani minden alosztalynal, ahol eltero kereso mezo van
     * @var string
     */
    protected $_searchTextField = 'search_text';

    /**
     * @var string
     */
    protected $_searchTerm;

    /**
     * @var ORM
     */
    protected $_model;

    /**
     * Project_Search_Simple constructor.
     * @param string $searchTerm
     */
    public function __construct($searchTerm)
    {
        $this->_searchTerm  = $searchTerm;
        $this->_model       = $this->createSearchModel();
    }

    /**
     * @return array
     */
    public function search()
    {
        /**
         * @var $models Array_Builder
         */
        $models = $this->getInitModels();

        if (!$this->_searchTerm) {
            return $models->execute()->as_array();
        }

        return $models->and_where($this->_searchTextField, 'LIKE', $this->_searchTerm)->execute()->as_array();
    }
}