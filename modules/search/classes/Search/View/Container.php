<?php

abstract class Search_View_Container
{
    /**
     * @var string
     */
    protected $_entityName;

    /**
     * Aktualis tipus (egyszeru, reszletes)
     * @var
     */
    protected $_currentType;

    /**
     * @var View_Container_Relation
     */
    protected $_relationContainer;

    /**
     * @var string
     */
    protected $_searchTerm;

    /**
     * @var string
     */
    protected $_simpleSubtitle;

    /**
     * @param string $entityName
     * @param $currentType
     */
    public function __construct($entityName, $currentType)
    {
        $this->_entityName  = $entityName;
        $this->_currentType = $currentType;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->_entityName;
    }

    /**
     * @return mixed
     */
    public function getCurrentType()
    {
        return $this->_currentType;
    }

    /**
     * @return View_Container_Relation
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
     * @return string
     */
    public function getSimpleSubtitle()
    {
        return $this->_simpleSubtitle;
    }
}