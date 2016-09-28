<?php

/**
 * Class Project_Search_Complex
 *
 * Reszletes kereses, iparagak, szakteruletek, kepessegek alapjan
 * Iparag, szakterulet mindig VAGY kapcsolattal keres, kepessegek eseten pedig a felhasznalo altal megadott kapcsolatban
 *
 * @author  Joo Martin
 * @since   2.2
 * @version 1.0
 */

class Project_Search_Complex implements Project_Search
{
    const SKILL_RELATION_OR         = 1;
    const SKILL_RELATION_AND        = 2;

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
     * @var array Az osszes projekt osszes kapcsolat azonositoja. Ebbol szedjuk ki az adott projekt azonositoit.
     * Ez a tomb cache -bol lekerdezheto, ezért hasznalja ezt.
     * Minden index egy projekt azonosito, minden elem egy tomb, ami tartalmazza a kapcsolatok azonositoit
     */
    private $_relationIdsByProjectIds    = [];

    public function __construct(
        array $searchedIndustryIds, array $searchedProfessionIds, array $searchedSkillIds, $skillRelation) {

        $this->_searchedIndustryIds     = $searchedIndustryIds;
        $this->_searchedProfessionIds   = $searchedProfessionIds;
        $this->_searchedSkillIds        = $searchedSkillIds;
        $this->_skillRelation           = $skillRelation;

        $this->_currentProject          = new Model_Project();
    }

    /**
     * @return array
     */
    public function getMatchedProjects()
    {
        return $this->_matchedProjects;
    }

    /**
     * @param array $projects
     */
    public function setProjects($projects)
    {
        $this->_projects = $projects;
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

    public function search()
    {
        $this->_projects        = $this->_currentProject->getActivesOrderedByCreated();

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

        $relationModelsByProjectIds     = $this->_searchedRelationModel->getAll();
        $this->_relationIdsByProjectIds = Business::getIdsFromModelsMulti(
            $relationModelsByProjectIds,
            Business_Project::getRelationIdField($this->_searchedRelationModel));

        // Elso keresesnel a matchedProjects ures lesz, aztan beletesszuk a talalatokat es a tobbi keresnel ezeket szukiti tovabb
        $projects           = (empty($this->_matchedProjects)) ? $this->_projects : $this->_matchedProjects;
        $matchedProjects    = [];

        foreach ($projects as $project) {
            $this->_currentProject = $project;

            if ($this->_searchedRelationModel instanceof Model_Project_Skill) {
                $found = $this->searchSkillsInOneProject();
            } else {
                $found = $this->searchRelationsInOneProject();
            }

            if ($found) {
                $matchedProjects[] = $this->_currentProject;
            }
        }

        $this->_matchedProjects = $matchedProjects;
    }

    protected function searchRelationsInOneProject()
    {
        foreach ($this->_searchedRelationIds as $searchedRelationId) {
            $found = $this->searchOneRelationInOneProject($searchedRelationId);

            if ($found) {
                return true;
            }
        }

        return false;
    }

    protected function searchOneRelationInOneProject($searchedRelationId)
    {
        $projectRelationIds = Arr::get($this->_relationIdsByProjectIds, $this->_currentProject->project_id, []);
        return in_array($searchedRelationId, $projectRelationIds);
    }

    protected function searchSkillsInOneProject()
    {
        $projectSkillIds = Arr::get($this->_relationIdsByProjectIds, $this->_currentProject->project_id, []);

        $difference = array_diff($this->_searchedRelationIds, $projectSkillIds);
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

    protected function isEmptySearch()
    {
        return empty($this->_searchedIndustryIds)
            && empty($this->_searchedProfessionIds) && empty($this->_searchedSkillIds);
    }

    protected function isSkillSearch()
    {
        return !empty($this->_searchedSkillIds);
    }

    protected function getSearchedRelationIdsByType()
    {
        // Egy get_class es switch szebb lenne, de unittest miatt NEM LEHET (Mock_xy osztalyok miatt)
        if ($this->_searchedRelationModel instanceof Model_Project_Industry) {
            return $this->_searchedIndustryIds;
        } elseif ($this->_searchedRelationModel instanceof Model_Project_Profession) {
            return $this->_searchedProfessionIds;
        } else {
            return $this->_searchedSkillIds;
        }
    }
}