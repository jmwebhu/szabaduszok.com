<?php

abstract class Search_Relation_Factory_User implements Search_Relation_Factory
{
    /**
     * @var Model_Relation
     */
    protected static $_relationModel;

    /**
     * @param Search_Complex $complex
     * @return Search_Relation
     */
    public static function makeSearch(Search_Complex $complex)
    {
        try {
            self::$_relationModel = $complex->getSearchedRelationModel();

            $search = new Search_Relation_Industry($complex);

            if (self::isIndustry()) {
                $search = new Search_Relation_Industry($complex);

            } elseif (self::isProfession()) {
                $search = new Search_Relation_Profession($complex);

            } elseif (self::isSkill()) {
                $search = new Search_Relation_Skill($complex);

            } else {
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
    protected static function isIndustry()
    {
        return (self::$_relationModel instanceof Model_User_Industry);
    }

    /**
     * @return bool
     */
    protected static function isProfession()
    {
        return (self::$_relationModel instanceof Model_User_Profession);
    }

    /**
     * @return bool
     */
    protected static function isSkill()
    {
        return (self::$_relationModel instanceof Model_User_Skill);
    }
}