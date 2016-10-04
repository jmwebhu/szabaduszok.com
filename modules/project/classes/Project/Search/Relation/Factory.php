<?php

class Project_Search_Relation_Factory
{
    protected static $_complex;

    /**
     * @param Project_Search_Complex $complex
     * @return Project_Search_Relation
     */
    public static function makeSearch(Project_Search_Complex $complex)
    {
        try {
            self::$_complex = $complex;
            $relation = $complex->getSearchedRelationModel();

            if ($relation instanceof Model_Project_Industry) {
                $search = self::makeForIndustry();

            } elseif ($relation instanceof Model_Project_Profession) {
                $search = self::makeForProfession();

            } elseif ($relation instanceof Model_Project_Skill) {
                $search = self::makeForSkill();
            } else {
                $search = self::makeForIndustry();

                throw new Exception('Project_Search_Relation not found for class: ' . get_class($relation));
            }
        } catch (Exception $ex) {
            Log::instance()->addException($ex);
        }

        return $search;
    }

    /**
     * @return Project_Search_Relation_Industry
     */
    protected static function makeForIndustry()
    {
        return new Project_Search_Relation_Industry(
            self::$_complex->getCurrentProject(),
            self::$_complex->getSearchedRelationIds(),
            self::$_complex->getRelationIdsByProjectIds());
    }

    /**
     * @return Project_Search_Relation_Profession
     */
    protected static function makeForProfession()
    {
        return new Project_Search_Relation_Profession(
            self::$_complex->getCurrentProject(),
            self::$_complex->getSearchedRelationIds(),
            self::$_complex->getRelationIdsByProjectIds());
    }

    /**
     * @return Project_Search_Relation_Skill
     */
    protected static function makeForSkill()
    {
        return new Project_Search_Relation_Skill(
            self::$_complex->getCurrentProject(),
            self::$_complex->getSearchedRelationIds(),
            self::$_complex->getRelationIdsByProjectIds(),
            self::$_complex->getSkillRelation());
    }
}