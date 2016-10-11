<?php

class Search_View_Container_Relation
{
    /**
     * @var array of Search_View_Container_Relation_Item
     */
    protected $_industries  = [];

    /**
     * @var array of Search_View_Container_Relation_Item
     */
    protected $_professions = [];

    /**
     * @var array of Search_View_Container_Relation_Item
     */
    protected $_skills      = [];

    /**
     * ES / VAGY kapcsolat
     * @var int
     */
    protected $_skillRelation;

    /**
     * @return array
     */
    public function getIndustries()
    {
        return $this->_industries;
    }

    /**
     * @return array
     */
    public function getProfessions()
    {
        return $this->_professions;
    }

    /**
     * @return array
     */
    public function getSkills()
    {
        return $this->_skills;
    }

    /**
     * @return int
     */
    public function getSkillRelation()
    {
        return $this->_skillRelation;
    }

    /**
     * @param int $skillRelation
     */
    public function setSkillRelation($skillRelation)
    {
        $this->_skillRelation = $skillRelation;
    }

    public function addItem(Search_View_Container_Relation_Item $item, $type)
    {
        switch ($type) {
            case Search_View_Container_Relation_Item::TYPE_INDUSTRY:
                $this->_industries[] = $item;
                break;

            case Search_View_Container_Relation_Item::TYPE_PROFESSION:
                $this->_professions[] = $item;
                break;

            case Search_View_Container_Relation_Item::TYPE_SKILL:
                $this->_skills[] = $item;
                break;
        }
    }
}