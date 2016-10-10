<?php

/**
 * Class Search_Relation_Skill
 *
 * Kepessegek keresesere szolgal
 */

class Search_Relation_Skill extends Search_Relation
{
    const SKILL_RELATION_OR         = 1;
    const SKILL_RELATION_AND        = 2;

    /**
     * @var int
     */
    protected $_skillRelation;

    /**
     * @param ORM $model
     * @param array $searchedRelationIds
     * @param array $relationIdsByModelIds
     * @param int $skillRelation
     */
    public function __construct(ORM $model, array $searchedRelationIds, array $relationIdsByModelIds, $skillRelation)
    {
        $this->_skillRelation = $skillRelation;
        parent::__construct($model, $searchedRelationIds, $relationIdsByModelIds);
    }

    /**
     * @return bool
     */
    public function searchRelationsInOneModel()
    {
        $modelSkillIds      = Arr::get($this->_relationIdsByModelIds, $this->_model->pk(), []);
        $difference         = array_diff($this->_searchedRelationIds, $modelSkillIds);
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