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

        $this->_currentModel            = $this->createSearchModel();
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
        $this->_models = $this->_currentModel->getOrderedByCreated();

        // Szukites iparagakra
        $this->searchRelationsInProjects(new Model_Project_Industry());

        // Szukites szakteruletekre
        $this->searchRelationsInProjects(new Model_Project_Profession());

        // Szukites kepessegekre
        $this->searchRelationsInProjects(new Model_Project_Skill());

        return $this->_matchedModels;
    }

    /**
     * Keresi a kapcsolatokat a projetkekben
     *
     * @param Model_Project_Relation $relationModel    Egy peldany a keresett kapcsolat modeljebol, pl.: Model_Project_Industry
     *                              unittest dependency injection miatt van ra szukseg, egyebkent eleg lenne a $this -ben
     *                              tarolt peldany
     *
     * @return bool
     */
    protected function searchRelationsInProjects(Model_Relation $relationModel)
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
            $this->_searchedRelationModel->primary_key());

        // Elso keresesnel a matchedModels ures lesz, aztan beletesszuk a talalatokat es a tobbi keresnel ezeket szukiti tovabb
        $models           = (empty($this->_matchedModels)) ? $this->_models : $this->_matchedModels;
        $matchedModels    = [];

        foreach ($models as $model) {
            $this->_currentModel    = $model;

            $this->_searchRelation  = Search_Relation_Factory::makeSearch($this);
            $found                  = $this->_searchRelation->searchRelationsInOneModel();

            if ($found) {
                $matchedModels[] = $this->_currentModel;
            }
        }

        $this->_matchedModels = $matchedModels;

        return true;
    }

    /**
     * @return bool
     */
    protected function isEmptySearch()
    {
        return empty($this->_searchedIndustryIds)
            && empty($this->_searchedProfessionIds) && empty($this->_searchedSkillIds);
    }
}