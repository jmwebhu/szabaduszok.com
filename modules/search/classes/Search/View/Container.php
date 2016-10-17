<?php

abstract class Search_View_Container
{
    /**
     * Aktualis tipus (egyszeru, reszletes)
     * @var string
     */
    protected $_currentType;

    /**
     * @var Search_View_Container_Relation
     */
    protected $_relationContainer;

    /**
     * @var string
     */
    protected $_searchTerm;

    /**
     * @param string $currentType
     */
    public function __construct($currentType)
    {
        $this->_currentType = $currentType;
    }

    /**
     * @return mixed
     */
    public function getCurrentType()
    {
        return $this->_currentType;
    }

    /**
     * @return Search_View_Container_Relation
     */
    public function getRelationContainer()
    {
        return $this->_relationContainer;
    }

    /**
     * @return string
     */
    public function getSearchTerm()
    {
        return $this->_searchTerm;
    }

    /**
     * @param Search_View_Container_Relation $relationContainer
     */
    public function setRelationContainer($relationContainer)
    {
        if ($this->_relationContainer == null) {
            $this->_relationContainer = $relationContainer;
        }
    }

    /**
     * @param string $searchTerm
     */
    public function setSearchTerm($searchTerm)
    {
        if ($this->_searchTerm == null) {
            $this->_searchTerm = $searchTerm;
        }
    }

    /**
     * @return string
     */
    abstract public function getSimpleSubtitle();

    /**
     * @return string
     */
    abstract public function getEntityNameForHuman();
}