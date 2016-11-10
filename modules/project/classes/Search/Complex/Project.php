<?php

/**
 * Class Search_Complex_Project
 *
 * Felelosseg: Reszletes kereses
 */

class Search_Complex_Project extends Search_Complex
{
    /**
     * @return Model_Project
     */
    public function createSearchModel()
    {
        return new Model_Project();
    }

    /**
     * @return Array_Builder
     */
    public function getInitModels()
    {
        $project = $this->_currentModel;
        if ($project == null) {
            $project = new Model_Project();
        }

        return $project->getOrderedByCreated(true);
    }

    /**
     * @return Search_Relation
     */
    protected function makeSearchRelation()
    {
        return Search_Relation_Factory_Project::makeSearch($this);
    }

    /**
     * @return string
     */
    public function getModelPrimaryKey()
    {
        return 'project_id';
    }

    /**
     * @return Model_Project_Industry
     */
    protected function getIndustryRelationModel()
    {
        return new Model_Project_Industry();
    }

    /**
     * @return Model_Project_Profession
     */
    protected function getProfessionRelationModel()
    {
        return new Model_Project_Profession();
    }

    /**
     * @return Model_Project_Skill
     */
    protected function getSkillRelationModel()
    {
        return new Model_Project_Skill();
    }

    /**
     * @return bool
     */
    protected function isDynamicSkillRelation()
    {
        return false;
    }
}