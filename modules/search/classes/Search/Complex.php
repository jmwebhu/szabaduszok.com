<?php

/**
 * Class Search_Complex
 *
 * Felelosseg: Reszletes kereses
 */

abstract class Search_Complex implements Search
{
    /**
     * @var array
     */
    protected $_searchedIndustryIds   = [];

    /**
     * @var array
     */
    protected $_searchedProfessionIds = [];

    /**
     * @var array
     */
    protected $_searchedSkillIds      = [];

    /**
     * @var array Mindig az aktualisan keresett kapcsolat azonositoit tartalmazza
     */
    protected $_searchedRelationIds   = [];

    /**
     * @var ORM Egy peldany a keresett kapcsolat osszekoto modeljebol, ez lehet Model_XY_Industry, vagy
     * Model_XY_Profession. Mivel a ket kapcsolat keresese ugyanugy van megvalositva, ezzel fogjuk megkulonboztetni
     * hogy eppen a $_searchedIndustryIds -bol vagy a $_searchedProfessionIds -bol kell keresni
     */
    protected $_searchedRelationModel;

    /**
     * @var int
     */
    protected $_skillRelation;

    /**
     * @var ORM
     */
    protected $_currentModel;

    /**
     * @var array
     */
    protected $_models          = [];

    /**
     * @var array
     */
    protected $_matchedModels     = [];

    /**
     * @var Search_Relation
     */
    protected $_searchRelation;

    /**
     * @var array Az osszes model osszes kapcsolat azonositoja. Ebbol szedjuk ki az adott model azonositoit.
     * Ez a tomb cache -bol lekerdezheto, ezért hasznalja ezt.
     * Minden index egy model azonosito, minden elem egy tomb, ami tartalmazza a kapcsolatok azonositoit
     */
    protected $_relationIdsByModelIds    = [];

    protected $_isFirstSearch = true;

    /**
     * @param array $searchedIndustryIds
     * @param array $searchedProfessionIds
     * @param array $searchedSkillIds
     * @param $skillRelation
     */
    public function __construct(
        array $searchedIndustryIds, array $searchedProfessionIds, array $searchedSkillIds, $skillRelation) {

        $this->_searchedIndustryIds     = $searchedIndustryIds;
        $this->_searchedProfessionIds   = $searchedProfessionIds;
        $this->_searchedSkillIds        = $searchedSkillIds;
        $this->_skillRelation           = $skillRelation;

        if ($this->_currentModel == null) {
            $this->_currentModel            = $this->createSearchModel();
        }
    }

    /**
     * @param array $models
     */
    public function setModels($models)
    {
        $this->_models = $models;
    }

    /**
     * @return array
     */
    public function getMatchedModels()
    {
        return $this->_matchedModels;
    }

    /**
     * @return Model_Relation
     */
    public function getSearchedRelationModel()
    {
        return $this->_searchedRelationModel;
    }

    /**
     * @return ORM
     */
    public function getCurrentModel()
    {
        return $this->_currentModel;
    }

    /**
     * @return array
     */
    public function getSearchedRelationIds()
    {
        return $this->_searchedRelationIds;
    }

    /**
     * @return array
     */
    public function getRelationIdsByModelIds()
    {
        return $this->_relationIdsByModelIds;
    }

    /**
     * @return mixed
     */
    public function getSkillRelation()
    {
        return $this->_skillRelation;
    }

    /**
     * @param ORM $currentModel
     */
    public function setCurrentModel($currentModel)
    {
        if ($this->_currentModel == null) {
            $this->_currentModel = $currentModel;
        }
    }

    /**
     * @return array
     */
    public function search()
    {
        $this->_models = $this->getInitModels();

        // Szukites iparagakra
        $this->searchRelationsInModels($this->getIndustryRelationModel());

        // Szukites szakteruletekre
        $this->searchRelationsInModels($this->getProfessionRelationModel());

        // Szukites kepessegekre
        $this->searchRelationsInModels($this->getSkillRelationModel());

        return $this->_matchedModels;
    }

    /**
     * @param Model_Relation $relationModel
     * @return bool
     */
    protected function searchRelationsInModels(Model_Relation $relationModel)
    {
        $this->_searchedRelationModel   = $relationModel;
        $this->_searchedRelationIds     = $this->{$this->_searchedRelationModel->getSearchedRelationIdsPropertyName()};

        if ($this->isEmptySearch()) {
            $this->_matchedModels = $this->_models;
            return true;
        }

        if (empty($this->_searchedRelationIds)) {
            return false;
        }

        /**
         * @todo AB hasznalata ha az IN operator stabil
         */

        $relationModelsByModelIds     = $this->_searchedRelationModel->getAll();
        $this->_relationIdsByModelIds = Business::getIdsFromModelsMulti(
            $relationModelsByModelIds,
            $relationModel->getPrimaryKeyForEndModel());

        // Elso keresesnel a matchedModels ures lesz, aztan beletesszuk a talalatokat es a tobbi keresnel ezeket szukiti tovabb
        $this->_matchedModels   = (empty($this->_matchedModels)) ? $this->_models : $this->_matchedModels;
        $matchedModels          = [];

        foreach ($this->_matchedModels as $model) {
            if ($this->isDynamicSkillRelation()) {
                $this->_skillRelation = $model->skill_relation;
            }

            $this->_currentModel    = $model;
            $this->_searchRelation  = $this->makeSearchRelation();
            $found                  = $this->_searchRelation->searchRelationsInOneModel();

            if ($this->needToAddCurrentToMatchedBy($found)) {
                $matchedModels[] = $this->_currentModel;
            }
        }

        $this->_matchedModels   = $matchedModels;
        $this->_isFirstSearch   = false;

        return true;
    }

    /**
     * @param bool $found
     * @return bool
     */
    protected function needToAddCurrentToMatchedBy($found)
    {
        $needToAdd = false;
        switch ($this->_isFirstSearch) {
            case true:
                $needToAdd = $found;
                break;

            case false:
                $needToAdd = $found && $this->isCurrentInMatchedModels();
                break;
        }

        return $needToAdd;
    }

    /**
     * @return bool
     */
    protected function isCurrentInMatchedModels()
    {
        foreach ($this->_matchedModels as $matchedModel) {
            if ($matchedModel->{$this->getModelPrimaryKey()} == $this->_currentModel->{$this->getModelPrimaryKey()}) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isEmptySearch()
    {
        return empty($this->_searchedIndustryIds)
            && empty($this->_searchedProfessionIds) && empty($this->_searchedSkillIds);
    }

    /**
     * @return Search_Relation
     */
    abstract protected function makeSearchRelation();

    /**
     * unittestben nem mukodik az ORM->pk(), ezert felul kell irni
     * @return string
     */
    abstract public function getModelPrimaryKey();

    /**
     * @return Model_Relation
     */
    abstract protected function getIndustryRelationModel();

    /**
     * @return Model_Relation
     */
    abstract protected function getProfessionRelationModel();

    /**
     * @return Model_Relation
     */
    abstract protected function getSkillRelationModel();

    /**
     * @return bool
     */
    abstract protected function isDynamicSkillRelation();
}