<?php

class Project_Search_Relation_Factory
{
    public static function makeSearch(Project_Search_Complex $complex)
    {
        try {
            $relation = $complex->getSearchedRelationModel();

            if ($relation instanceof Model_Project_Industry) {
                $search = new Project_Search_Relation_Industry(
                    $complex->getCurrentProject(),
                    $complex->getSearchedRelationIds(),
                    $complex->getRelationIdsByProjectIds());

            } elseif ($relation instanceof Model_Project_Profession) {
                $search = new Project_Search_Relation_Profession(
                    $complex->getCurrentProject(),
                    $complex->getSearchedRelationIds(),
                    $complex->getRelationIdsByProjectIds());

            } elseif ($relation instanceof Model_Project_Skill) {
                $search = new Project_Search_Relation_Skill(
                    $complex->getCurrentProject(),
                    $complex->getSearchedRelationIds(),
                    $complex->getRelationIdsByProjectIds(),
                    $complex->getSkillRelation());
            } else {
                throw new Exception('Project_Search_Relation not found for class: ' . get_class($relation));
            }
        } catch (Exception $ex) {
            Log::instance()->add(Log::ERROR, $ex->getMessage() . ' Trace: ' . $ex->getTraceAsString());
        }

        return $search;
    }
}