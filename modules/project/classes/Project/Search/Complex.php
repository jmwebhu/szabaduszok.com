<?php

/**
 * Class Project_Search_Complex
 *
 * Reszletes kereses, iparagak, szakteruletek, kepessegek alapjan
 * Iparag, szakterulet mindig VAGY kapcsolattal keres, kepessegek eseten pedig a felhasznalo altal megadott kapcsolatban
 */

class Project_Search_Complex implements Project_Search
{
    private $_searchedIndustryIds   = [];
    private $_searchedProfessionIds = [];
    private $_searchedSkillIds      = [];

    /**
     * @var array Mindig az aktualisan keresett kapcsolat azonositoit tartalmazza
     */
    private $_searchedRelationIds   = [];

    /**
     * @var ORM Egy peldany a keresett kapcsolat osszekoto modeljebol, ez lehet Model_Project_Industry, vagy
     * Model_Project_Profession. Mivel a ket kapcsolat keresese ugyanugy van megvalositva, ezzel fogjuk megkulonboztetni
     * hogy eppen a $_searchedIndustryIds -bol vagy a $_searchedProfessionIds -bol kell keresni
     */
    private $_searchedRelationModel;

    private $_skillRelation;
    private $_currentProject;

    private $_projects          = [];
    private $_matchedProjects   = [];

    /**
     * @var Project_Search_Relation
     */
    private $_searchRelation;

    /**
     * @var array Az osszes projekt osszes kapcsolat azonositoja. Ebbol szedjuk ki az adott projekt azonositoit.
     * Ez a tomb cache -bol lekerdezheto, ezért hasznalja ezt.
     * Minden index egy projekt azonosito, minden elem egy tomb, ami tartalmazza a kapcsolatok azonositoit
     */
    private $_relationIdsByProjectIds    = [];

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

        $this->_currentProject          = new Model_Project();
    }

    /**
     * @param array $projects
     */
    public function setProjects($projects)
    {
        $this->_projects = $projects;
    }

    /**
     * @return array
     */
    public function getMatchedProjects()
    {
        return $this->_matchedProjects;
    }

    /**
     * @return ORM
     */
    public function getSearchedRelationModel()
    {
        return $this->_searchedRelationModel;
    }

    /**
     * @return Model_Project
     */
    public function getCurrentProject()
    {
        return $this->_currentProject;
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
    public function getRelationIdsByProjectIds()
    {
        return $this->_relationIdsByProjectIds;
    }

    /**
     * @return mixed
     */
    public function getSkillRelation()
    {
        return $this->_skillRelation;
    }

    /**
     * @param Model_Project $currentProject
     */
    public function setCurrentProject($currentProject)
    {
        if ($this->_currentProject == null) {
            $this->_currentProject = $currentProject;
        }
    }

    /**
     * @return array
     */
    public function search()
    {
        $this->_projects = $this->_currentProject->getOrderedByCreated();

        // Szukites iparagakra
        $this->searchRelationsInProjects(new Model_Project_Industry());

        // Szukites szakteruletekre
        $this->searchRelationsInProjects(new Model_Project_Profession());

        // Szukites kepessegekre
        $this->searchRelationsInProjects(new Model_Project_Skill());

        return $this->_matchedProjects;
    }

    /**
     * Keresi a kapcsolatokat a projetkekben
     *
     * @param ORM $relationModel    Egy peldany a keresett kapcsolat modeljebol, pl.: Model_Project_Industry
     *                              unittest dependency injection miatt van ra szukseg, egyebkent eleg lenne a $this -ben
     *                              tarolt peldany
     *
     * @return bool
     */
    protected function searchRelationsInProjects(ORM $relationModel)
    {
        $this->_searchedRelationModel   = $relationModel;
        $this->_searchedRelationIds     = $this->getSearchedRelationIdsByType();

        if ($this->isEmptySearch()) {
            $this->_matchedProjects = $this->_projects;
            return true;
        }

        if (empty($this->_searchedRelationIds)) {
            return false;
        }

        /**
         * @todo AB hasznalata ha az IN operator stabil
         */

        $relationModelsByProjectIds     = $this->_searchedRelationModel->getAll();
        $this->_relationIdsByProjectIds = Business::getIdsFromModelsMulti(
            $relationModelsByProjectIds,
            Business_Project::getRelationIdField($this->_searchedRelationModel));

        // Elso keresesnel a matchedProjects ures lesz, aztan beletesszuk a talalatokat es a tobbi keresnel ezeket szukiti tovabb
        $projects           = (empty($this->_matchedProjects)) ? $this->_projects : $this->_matchedProjects;
        $matchedProjects    = [];

        foreach ($projects as $project) {
            $this->_currentProject = $project;

            $this->_searchRelation  = Project_Search_Relation_Factory::makeSearch($this);
            $found                  = $this->_searchRelation->searchRelationsInOneProject();

            if ($found) {
                $matchedProjects[] = $this->_currentProject;
            }
        }

        $this->_matchedProjects = $matchedProjects;

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

    /**
     * @return array
     */
    protected function getSearchedRelationIdsByType()
    {
        // Egy get_class es switch szebb lenne, de unittest miatt NEM LEHET (Mock_xy osztalyok miatt)
        if ($this->_searchedRelationModel instanceof Model_Project_Industry) {
            return $this->_searchedIndustryIds;
        } elseif ($this->_searchedRelationModel instanceof Model_Project_Profession) {
            return $this->_searchedProfessionIds;
        } elseif ($this->_searchedRelationModel instanceof Model_Project_Skill) {
            return $this->_searchedSkillIds;
        }

        return $this->_searchedIndustryIds;
    }
}