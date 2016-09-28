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

    public function search()
    {
        $this->_projects     = $this->_currentProject->getActivesOrderedByCreated();

        // Szukites iparagakra
        $this->searchRelationsInProjects(new Model_Project_Industry());

        // Szukites szakteruletekre
        $this->searchRelationsInProjects(new Model_Project_Profession());

        $this->searchSkillsInProjects(new Model_Project_Skill());

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

        if (empty($this->_searchedRelationIds)) {
            $this->_matchedProjects = $this->_projects;
            return true;
        }

        $relationModelsByProjectIds     = $this->_searchedRelationModel->getAll();
        $this->_relationIdsByProjectIds = Business::getIdsFromModelsMulti($relationModelsByProjectIds, Business_Project::getRelationIdField($relationModel));

        foreach ($this->_projects as $project) {
            $this->_currentProject = $project;
            $this->searchRelationsInOneProject();
        }
    }

    protected function searchRelationsInOneProject()
    {
        foreach ($this->_searchedRelationIds as $searchedRelationId) {
            $found = $this->searchOneRelationInOneProject($searchedRelationId);

            if ($found) {
                $this->_matchedProjects[] = $this->_currentProject;
            }
        }
    }

    protected function searchOneRelationInOneProject($searchedRelationId)
    {
        $projectRelationIds = Arr::get($this->_relationIdsByProjectIds, $this->_currentProject->project_id, []);

        return in_array($searchedRelationId, $projectRelationIds);
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

    // Ugyanaz
    protected function searchSkillsInProjects(Model_Project_Skill $projectSkill)
    {
        if (empty($this->_searchedSkillIds)) {
            return $this->_projects;
        }

        $result                         = [];
        $this->_searchedRelationModel   = $projectSkill;

        foreach ($this->_projects as $project) {
            $this->_currentProject = $project;
            $found = $this->searchSkillsInOneProject();

            if ($found) {
                $result[] = $project;
            }
        }

        return $result;
    }

    // Kulonbsegek: empty vizsgalat, mashol break helyett return van
    protected function searchSkillsInOneProject()
    {
        // Osszes projekthez tartozo osszes kepesseg
        $relationModelsByProjectIds     = $this->_searchedRelationModel->getAll();
        $this->_relationIdsByProjectIds = Business::getIdsFromModelsMulti($relationModelsByProjectIds);

        $found                      = ($this->_skillRelation == self::SKILL_RELATION_OR) ? false : true;

        if (empty($projectSkillIds)) {
            return true;
        }

        // Vegmegy a postban kapott, keresett kepessegeken
        foreach ($this->_searchedRelationIds as $searchedSkillId) {
            $found = $this->searchOneSkillInOneProjectByRelation($searchedSkillId);

            if ($found) {
                break;
            }
        }

        return $found;
    }

    // Kulonbsegek: mashol siman visszater az in_array ertekevel
    protected function searchOneSkillInOneProjectByRelation($searchedSkillId)
    {
        $result = false;
        $projectSkillIds = Arr::get($this->_relationIdsByProjectIds, $this->_currentProject->project_id, []);
        $found = in_array($searchedSkillId, $projectSkillIds);

        // Vagy eseten, ha legalabb egy talalt volt, akkor leallitja a keresest
        if ($this->_skillRelation == self::SKILL_RELATION_OR && $found) {
            $result = true;
        }

        // Es eseten, ha legalabb az egyik kepesseg nem talalhato, akkor leallitja a keresest
        if ($this->_skillRelation == self::SKILL_RELATION_AND && !$found) {
            $result = true;
        }

        return $result;
    }
}