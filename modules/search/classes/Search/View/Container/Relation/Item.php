<?php

abstract class Search_View_Container_Relation_Item
{
    /**
     * @var int
     */
    const TYPE_INDUSTRY     = 1;

    /**
     * @var int
     */
    const TYPE_PROFESSION   = 2;

    /**
     * @var int
     */
    const TYPE_SKILL        = 3;

    /**
     * @var array of ORMs
     */
    protected $_items = [];

    /**
     * ES / VAGY kapcsolat
     * @var int
     */
    protected $_relation;

    /**
     * @var int
     */
    protected $_type;

    /**
     * @param array $items
     */
    public function __construct(array $items, $type)
    {
        $this->_items   = $items;
        $this->_type    = $type;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * @return int
     */
    public function getRelation()
    {
        return $this->_relation;
    }

    /**
     * @param int $relation
     */
    public function setRelation($relation)
    {
        $this->_relation = $relation;
    }

    /**
     * @return string
     */
    abstract public function getSubtitle();
}