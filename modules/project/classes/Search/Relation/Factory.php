<?php

abstract class Search_Relation_Factory
{
    /**
     * @var Search_Complex
     */
    protected static $_complex;

    protected static $_relationModel;

    /**
     * @return bool
     */
    protected static function isIndustry()
    {
        return (self::$_relationModel instanceof Model_Project_Industry);
    }

    /**
     * @return bool
     */
    protected static function isProfession()
    {
        return (self::$_relationModel instanceof Model_Project_Profession);
    }

    /**
     * @return bool
     */
    protected static function isSkill()
    {
        return (self::$_relationModel instanceof Model_Project_Skill);
    }

    /**
     * @return Search_Relation_Industry
     */
    protected static function makeForIndustry()
    {
        return new Search_Relation_Industry(
            self::$_complex->getCurrentModel(),
            self::$_complex->getSearchedRelationIds(),
            self::$_complex->getRelationIdsByModelIds());
    }
    /**
     * @return Search_Relation_Profession
     */
    protected static function makeForProfession()
    {
        return new Search_Relation_Profession(
            self::$_complex->getCurrentModel(),
            self::$_complex->getSearchedRelationIds(),
            self::$_complex->getRelationIdsByModelIds());
    }
    /**
     * @return Search_Relation_Skill
     */
    protected static function makeForSkill()
    {
        return new Search_Relation_Skill(
            self::$_complex->getCurrentModel(),
            self::$_complex->getSearchedRelationIds(),
            self::$_complex->getRelationIdsByModelIds(),
            self::$_complex->getSkillRelation());
    }
}