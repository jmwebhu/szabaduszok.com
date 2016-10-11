<?php

abstract class View_Container_Relation_Item
{
    /**
     * @var array of ORMs
     */
    protected $_items = [];

    /**
     * @var string
     */
    protected $_subtitle;

    /**
     * ES / VAGY kapcsolat
     * @var int
     */
    protected $_relation;

    /**
     * @param array $items
     * @param string $subtitle
     */
    public function __construct(array $items, $subtitle)
    {
        $this->_items       = $items;
        $this->_subtitle    = $subtitle;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->_subtitle;
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
}