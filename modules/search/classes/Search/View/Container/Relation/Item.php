<?php

class Search_View_Container_Relation_Item
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
     * @param ORM $model
     * @param int $type
     * @param bool $selected
     */
    public function __construct(ORM $model, $type, $selected)
    {
        $this->_model       = $model;
        $this->_type        = $type;
        $this->_selected    = $selected;
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
     * @return int
     */
    public function getId()
    {
        return $this->_model->pk();
    }

    /**
     * @return string
     */
    public function getName()
    {
        $name = ($this->_model->_nameField) ? $this->_model->_nameField : 'name';
        return $this->_model->{$name};
    }
}