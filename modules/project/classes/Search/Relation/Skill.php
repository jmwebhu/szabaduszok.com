<?php

/**
 * Class Project_Search_Relation_Skill
 *
 * Kepessegek keresesere szolgal
 */

class Project_Search_Relation_Skill extends Project_Search_Relation
{
    const SKILL_RELATION_OR         = 1;
    const SKILL_RELATION_AND        = 2;

    /**
     * @var int
     */
    protected $_skillRelation;

    /**
     * @param Model_Project $project
     * @param array $searchedRelationIds
     * @param array $relationIdsByProjectIds
     * @param int $skillRelation
     */
    public function __construct(Model_Project $project, array $searchedRelationIds, array $relationIdsByProjectIds, $skillRelation)
    {
        $this->_skillRelation = $skillRelation;
        parent::__construct($project, $searchedRelationIds, $relationIdsByProjectIds);
    }

    /**
     * @return bool
     */
    public function searchRelationsInOneProject()
    {
        $projectSkillIds    = Arr::get($this->_relationIdsByProjectIds, $this->_project->project_id, []);
        $difference         = array_diff($this->_searchedRelationIds, $projectSkillIds);
        $found              = false;

        switch ($this->_skillRelation) {
            case self::SKILL_RELATION_OR:
                $found = count($difference) != count($this->_searchedRelationIds);
                break;

            case self::SKILL_RELATION_AND:
                $found = empty($difference);
                break;
        }

        return $found;
    }
}