<?php

class Search_View_Container_Relation
{
    /**
     * @var Search_View_Container_Relation_Item
     */
    protected $_industries;

    /**
     * @var Search_View_Container_Relation_Item
     */
    protected $_professions;

    /**
     * @var Search_View_Container_Relation_Item
     */
    protected $_skills;

    /**
     * @param Search_View_Container_Relation_Item $industries
     * @param Search_View_Container_Relation_Item $professions
     * @param Search_View_Container_Relation_Item $skills
     */
    public function __construct(
        Search_View_Container_Relation_Item $industries, Search_View_Container_Relation_Item $professions,
        Search_View_Container_Relation_Item $skills)
    {
        $this->_industries  = $industries;
        $this->_professions = $professions;
        $this->_skills      = $skills;
    }

    /**
     * @return Search_View_Container_Relation_Item
     */
    public function getIndustries()
    {
        return $this->_industries;
    }

    /**
     * @return Search_View_Container_Relation_Item
     */
    public function getProfessions()
    {
        return $this->_professions;
    }

    /**
     * @return Search_View_Container_Relation_Item
     */
    public function getSkills()
    {
        return $this->_skills;
    }
}