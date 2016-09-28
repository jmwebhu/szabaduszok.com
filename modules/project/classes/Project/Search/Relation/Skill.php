<?php

class Project_Search_Relation_Skill extends Project_Search_Relation
{
    protected $_skillRelation;

    /**
     * Project_Search_Relation_Skill constructor.
     * @param $_skillRelation
     */
    public function __construct($_project, array $_searchedRelationIds, array $_relationIdsByProjectIds, $_skillRelation)
    {
        $this->_skillRelation = $_skillRelation;
        parent::__construct($_project, $_searchedRelationIds, $_relationIdsByProjectIds);
    }

    public function searchRelationsInOneProject()
    {
        $projectSkillIds = Arr::get($this->_relationIdsByProjectIds, $this->_project->project_id, []);

        $difference = array_diff($this->_searchedRelationIds, $projectSkillIds);
        switch ($this->_skillRelation) {
            case Project_Search_Complex::SKILL_RELATION_OR:
                $found = count($difference) != count($this->_searchedRelationIds);
                break;

            case Project_Search_Complex::SKILL_RELATION_AND:
                $found = empty($difference);
                break;
        }

        return $found;
    }
}