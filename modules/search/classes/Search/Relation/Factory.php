<?php

abstract class Search_Relation_Factory
{
    /**
     * @var Search_Complex
     */
    protected static $_complex;

    protected static $_relationModel;

    /**
     * Template method
     *
     * @param Search_Complex $complex
     * @return Search_Relation
     */
    public static function makeSearch(Search_Complex $complex)
    {
        try {
            self::$_complex = $complex;
            self::$_relationModel = $complex->getSearchedRelationModel();

            if (self::isIndustry()) {
                $search = self::makeForIndustry();

            } elseif (self::isProfession()) {
                $search = self::makeForProfession();

            } elseif (self::isSkill()) {
                $search = self::makeForSkill();

            } else {
                $search = self::makeForIndustry();
                throw new Exception('Search_Relation not found for class: ' . get_class(self::$_relationModel));
            }
        } catch (Exception $ex) {
            Log::instance()->addException($ex);
        }

        return $search;
    }

    /**
     * @return bool
     */
    abstract protected static function isIndustry();

    /**
     * @return bool
     */
    abstract protected static function isProfession();

    /**
     * @return bool
     */
    abstract protected static function isSkill();

    /**
     * @return Search_Relation_Industry
     */
    abstract protected static function makeForIndustry();

    /**
     * @return Search_Relation_Profession
     */
    abstract protected static function makeForProfession();

    /**
     * @return Search_Relation_Skill
     */
    abstract protected static function makeForSkill();
}