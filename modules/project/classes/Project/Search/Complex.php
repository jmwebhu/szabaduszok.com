<?php

/**
 * Class Project_Search_Complex
 *
 * Reszletes kereses, iparagak, szakteruletek, kepessegek alapjan
 *
 * @author  Joo Martin
 * @since   2.2
 * @version 1.0
 */

class Project_Search_Complex implements Project_Search
{
    /**
     * @var int Keresett Kepessegek kozti VAGY kapcsolat
     */
    const SKILL_RELATION_OR = 1;

    /**
     * @var int Keresett Kepessegek kozti ES kapcsolat
     */
    const SKILL_RELATION_AND = 2;

    /**
     * @var array Keresett iparagak azonositoi
     */
    private $_searchedIndustryIds = [];

    /**
     * @var array Keresett szakteruletek azonositoi
     */
    private $_searchedProfessionIds = [];

    /**
     * @var array Keresett kepessegek azonositoi
     */
    private $_searchedSkillIds = [];

    /**
     * @var int Keresett kepessegek kozti kapcsolat (ES, VAGY)
     */
    private $_skillRelation;

    /**
     * @var Model_Project Mindig az aktualisan keresett projekt peldanya
     */
    private $_currentProject;

    /**
     * @var array Keresesi talalatok
     */
    private $_matchedProjects = [];

    /**
     * @var ORM Egy peldany a keresett kapcsolat osszekoto modeljebol, ez lehet Model_Project_Industry, vagy
     * Model_Project_Profession. Mivel a ket kapcsolat keresese ugyanugy van megvalositva, ezzel fogjuk megkulonboztetni
     * hogy eppen a $_searchedIndustryIds -bol vagy a $_searchedProfessionIds -bol kell keresni
     */
    private $_searchedRelationModel;

    /**
     * @var array Mindig az aktualisan keresett kapcsolat azonositoit tartalmazza
     */
    private $_searchedRelationIds = [];

    /**
     * @var array Az osszes projekt osszes kapcsolat azonositoja. Ebbol szedjuk ki az adott projekt azonositoit
     */
    private $_allRelationIds = [];

    /**
     * Project_Search_Complex constructor.
     *
     * @param array $searchedIndustryIds
     * @param array $searchedProfessionIds
     * @param array $searchedSkillIds
     * @param int $skillRelation
     */
    public function __construct(
        array $searchedIndustryIds, array $searchedProfessionIds, array $searchedSkillIds, $skillRelation)
    {
        $this->_searchedIndustryIds     = $searchedIndustryIds;
        $this->_searchedProfessionIds   = $searchedProfessionIds;
        $this->_searchedSkillIds        = $searchedSkillIds;
        $this->_skillRelation           = $skillRelation;

        $this->_currentProject          = new Model_Project();
    }

    /**
     * @return Model_Project
     */
    public function getCurrentProject()
    {
        return $this->_currentProject;
    }

    /**
     * @param Model_Project $currentProject
     */
    public function setCurrentProject($currentProject)
    {
        $this->_currentProject = $currentProject;
    }

    /**
     * @return array
     */
    public function getSearchedIndustryIds()
    {
        return $this->_searchedIndustryIds;
    }

    /**
     * @param array $searchedIndustryIds
     */
    public function setSearchedIndustryIds($searchedIndustryIds)
    {
        $this->_searchedIndustryIds = $searchedIndustryIds;
    }

    /**
     * @return array
     */
    public function getSearchedProfessionIds()
    {
        return $this->_searchedProfessionIds;
    }

    /**
     * @param array $searchedProfessionIds
     */
    public function setSearchedProfessionIds($searchedProfessionIds)
    {
        $this->_searchedProfessionIds = $searchedProfessionIds;
    }

    /**
     * @return array
     */
    public function getSearchedSkillIds()
    {
        return $this->_searchedSkillIds;
    }

    /**
     * @param array $searchedSkillIds
     */
    public function setSearchedSkillIds($searchedSkillIds)
    {
        $this->_searchedSkillIds = $searchedSkillIds;
    }

    /**
     * @return int
     */
    public function getSkillRelation()
    {
        return $this->_skillRelation;
    }

    /**
     * @param int $searchSkillRelation
     */
    public function setSkillRelation($searchSkillRelation)
    {
        $this->_skillRelation = $searchSkillRelation;
    }

    /**
     * @return array
     */
    public function getMatchedProjects()
    {
        return $this->_matchedProjects;
    }

    /**
     * @param array $matchedProjects
     */
    public function setMatchedProjects($matchedProjects)
    {
        $this->_matchedProjects = $matchedProjects;
    }

    /**
     * @return ORM
     */
    public function getSearchedRelationModel()
    {
        return $this->_searchedRelationModel;
    }

    /**
     * @param ORM $searchedRelationModel
     */
    public function setSearchedRelationModel($searchedRelationModel)
    {
        $this->_searchedRelationModel = $searchedRelationModel;
    }

    /**
     * @return array
     */
    public function getAllRelationIds()
    {
        return $this->_allRelationIds;
    }

    /**
     * @param array $allRelationIds
     */
    public function setAllRelationIds($allRelationIds)
    {
        $this->_allRelationIds = $allRelationIds;
    }

    /**
     * Osszetett, felteteles kereses
     *
     * @return array                    Talalatok
     */
    public function search()
    {
        // Osszes aktiv projekt
        $this->_matchedProjects         = $this->_currentProject->getActivesOrderedByCreated();

        // Szukites iparagakra
        $this->_matchedProjects         = $this->searchRelationsInProjects(new Model_Project_Industry());

        // Szukites szakteruletekre
        $this->_matchedProjects         = $this->searchRelationsInProjects(new Model_Project_Profession());

        // Szukites kepessegekre
        //$this->_matchedProjects         = $this->searchSkillsInProjects(new Model_Project_Skill());

        return $this->_matchedProjects;
    }

    /**
     * Keresi a kapcsolatokat a projetkekben
     *
     * @param ORM $relationModel    Egy peldany a keresett kapcsolat modeljebol, pl.: Model_Project_Industry
     *                              unittest dependeny injection miatt van ra szukseg, egyebkent eleg lenne a $this -ben
     *                              tarolt peldany
     *
     * @return array                Azok a projektek, amikben megtalalhato legalabb egy, a keresett azonositokbol
     */
    protected function searchRelationsInProjects(ORM $relationModel)
    {
        $result                         = [];

        $this->_searchedRelationModel   = $relationModel;

        // Keresendo tomb beallitasa
        $this->_searchedRelationIds     = $this->getSearchedRelationIds();

        // Nincs keresendo adat
        if (empty($this->_searchedRelationIds)) {
            return $this->_matchedProjects;
        }

        foreach ($this->_matchedProjects as $project) {
            $this->_currentProject = $project;
            $found = $this->searchRelationsInOneProject();

            if ($found) {
                $result[] = $project;
            }
        }

        return $result;
    }

    /**
     * A kapott projektben keresi kapcsolatokat
     *
     * @return bool     true, ha a keresett kapcsolatok kozul legalabb az egyik megtalalhato a projektben
     */
    protected function searchRelationsInOneProject()
    {
        // Osszes projekt osszes kapcsolata. Azert ezzel dolgozik, mert ez benn van cache -ben, igy nem kell lekerdezni semmit
        $allRelationModel = $this->_searchedRelationModel->getAll();
        $this->_allRelationIds = Business::getIdsFromModelsMulti($allRelationModel);

        // Vegmegy a keresett kapcsolatokon. Ha barmelyik megtalalhato a projekt kapcsolatai kozt, true
        foreach ($this->_searchedRelationIds as $searchedRelationId) {
            $found = $this->searchOneRelationInOneProject($searchedRelationId);

            if ($found) {
                return true;
            }
        }

        return false;
    }

    /**
     * Az aktualis projekt kapcsolatai kozott keresi a kapott kapcsolat azonostitot
     *
     * @param $searchedRelationId           Keresett kapcsolat azonosito
     *
     * @return bool                         true, ha a keresett kapcsolat megtalalhato a projekt kapcsolatai kozott
     */
    protected function searchOneRelationInOneProject($searchedRelationId)
    {
        $projectRelationIds = Arr::get($this->_allRelationIds, $this->_currentProject->pk(), []);

        // Ha a keresett kapott kapcsolat megtalalhato a projekt kapcsolatai kozott
        if (in_array($searchedRelationId, $projectRelationIds)) {
            return true;
        }

        return false;
    }

    /**
     * A $_searchedRelationModel tipusa alapjan visszaadja azt a tombot, amelyikben keresni kell.
     * Pl.: Ha Model_Project_Industry tipus, akkor a _searchedIndustryIds adja vissza
     *
     * @return array                Kapcsolat azonositok
     */
    protected function getSearchedRelationIds()
    {
        $class = get_class($this->_searchedRelationModel);

        switch ($class) {
            case Model_Project_Industry::class: default:
                return $this->_searchedIndustryIds;

            case Model_Project_Profession::class:
                return $this->_searchedProfessionIds;
        }
    }

    /**
     * A kapott projekteket szukiti a kapott kepessegek alapjan.
     * skill_relation -tol fugg, hogy VAGY / ES kapcsolat van a keresett kepessegek kozott
     *
     * @param array $projects			Projektek (alapesetben a szakteruletekre szukitett projektek)
     * @param array $searchedSkillIds			POST kepessegek
     * @param int 	$skillRelation		Keresett kepessegek kapcsolata (ES / VAGY)
     *
     * @return array
     */
    protected function searchSkillsInProjects(array $projects, array $searchedSkillIds, $skillRelation, Model_Project_Skill $projectSkill)
    {
        if (empty($searchedSkillIds)) {
            return $projects;
        }

        $result = [];

        foreach ($projects as $project) {
            /**
             * @var $project Model_Project
             */
            $found = $this->searchSkillsInOneProject($project, $searchedSkillIds, $skillRelation, $projectSkill);

            if ($found) {
                $result[] = $project;
            }
        }

        return $result;
    }

    /**
     * Visszaadja, hogy a kapott projektben megtalalhatok -e a kapott kepessegek
     *
     * @param Model_Project $project		        Projekt
     * @param array $searchedSkillIds		                Keresett kepessegek
     * @param Model_Project_Skill $projectSkill     Ures model
     * @param int $skillRelation                         ES / VAGY kapcsolat
     *
     * @return bool
     */
    protected function searchSkillsInOneProject(Model_Project $project, array $searchedSkillIds, $skillRelation, Model_Project_Skill $projectSkill)
    {
        // Osszes projekthez tartozo osszes kepesseg
        $allRelationIds     = $projectSkill->getAll();

        // Projekt kepessegei
        $projectSkillIds    = Arr::get($allRelationIds, $project->project_id, []);
        $found              = ($skillRelation == self::SKILL_RELATION_OR) ? false : true;

        // Ha nincs a projekthez kepesseg
        if (empty($projectSkillIds)) {
            return true;
        }

        // Vegmegy a postban kapott, keresett kepessegeken
        foreach ($searchedSkillIds as $searchedSkillId) {
            $found = in_array($searchedSkillId, $projectSkillIds);

            // Vagy eseten, ha legalabb egy talalt volt, akkor leallitja a keresest
            if ($skillRelation == self::SKILL_RELATION_OR && $found) {
                break;
            }

            // Es eseten, ha legalabb az egyik kepesseg nem talalhato, akkor leallitja a keresest
            if ($skillRelation == self::SKILL_RELATION_AND && !$found) {
                break;
            }
        }

        return $found;
    }
}