<?php

abstract class View_Container_Relation
{
    /**
     * @var View_Container_Relation_Item
     */
    protected $_industries;

    /**
     * @var View_Container_Relation_Item
     */
    protected $_professions;

    /**
     * @var View_Container_Relation_Item
     */
    protected $_skills;

    /**
     * @param View_Container_Relation_Item $industries
     * @param View_Container_Relation_Item $professions
     * @param View_Container_Relation_Item $skills
     */
    public function __construct(
        View_Container_Relation_Item $industries, View_Container_Relation_Item $professions,
        View_Container_Relation_Item $skills)
    {
        $this->_industries = $industries;
        $this->_professions = $professions;
        $this->_skills = $skills;
    }

    /**
     * @return View_Container_Relation_Item
     */
    public function getIndustries()
    {
        return $this->_industries;
    }

    /**
     * @return View_Container_Relation_Item
     */
    public function getProfessions()
    {
        return $this->_professions;
    }

    /**
     * @return View_Container_Relation_Item
     */
    public function getSkills()
    {
        return $this->_skills;
    }


}