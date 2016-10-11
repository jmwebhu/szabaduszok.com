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
     * @var ORM
     */
    protected $_model;

    /**
     * @var int
     */
    protected $_type;

    /**
     * @var bool
     */
    protected $_selected;

    /**
     * Search_View_Container_Relation_Item constructor.
     * @param ORM $_model
     * @param int $_type
     */
    public function __construct(ORM $_model, $_type)
    {
        $this->_model   = $_model;
        $this->_type    = $_type;
    }

    /**
     * @return ORM
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return boolean
     */
    public function isSelected()
    {
        return $this->_selected;
    }

    /**
     * @param boolean $selected
     */
    public function setSelected($selected)
    {
        $this->_selected = $selected;
    }

    /**
     * @return string
     */
    abstract public function getSubtitle();
}