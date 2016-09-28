<?php

class Business_Project
{
    public static function getRelationIdField(ORM $relation)
    {
        if ($relation instanceof Model_Project_Industry) {
            return 'industry_id';
        } elseif ($relation instanceof Model_Project_Profession) {
            return 'profession_id';
        } elseif ($relation instanceof Model_Project_Skill) {
            return 'skill_id';
        }
    }
}