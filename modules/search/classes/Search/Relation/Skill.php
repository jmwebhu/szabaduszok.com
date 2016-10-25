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
     * @param Search_Complex $complex
     */
    public function __construct(Search_Complex $complex)
    {
        $this->_skillRelation = $complex->getSkillRelation();
        parent::__construct($complex);
    }

    /**
     * @return bool
     */
    public function searchRelationsInOneModel()
    {
        $model              = $this->_complexSearch->getCurrentModel();
        $modelRelationIds   = Arr::get(
            $this->_complexSearch->getRelationIdsByModelIds(),
            $model->{$this->_complexSearch->getModelPrimaryKey()}, []);

        $difference         = array_diff($this->_complexSearch->getSearchedRelationIds(), $modelRelationIds);
        $found              = false;

        switch ($this->_skillRelation) {
            case self::SKILL_RELATION_OR:
                $found = count($difference) != count($this->_complexSearch->getSearchedRelationIds());
                break;

            case self::SKILL_RELATION_AND:
                $found = empty($difference);
                break;
        }

        return $found;
    }

    /**
     * @return string
     */
    protected function getModelPk()
    {
        return 'skill_id';
    }
}