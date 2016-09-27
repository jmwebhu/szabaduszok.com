<?php

class Project_Search_Complex implements Project_Search
{
    /**
     * Keresett Kepessegek kozti VAGY kapcsolat
     * @var int
     */
    const SKILL_RELATION_OR = 1;
    /**
     * Keresett Kepessegek kozti ES kapcsolat
     * @var int
     */
    const SKILL_RELATION_AND = 2;

    /**
     * Osszetett, felteteles kereses
     *
     * @param array $data               Keresesi feltetelek
     * @param Model_Project $project    Ures project
     *
     * @return array                    Talalatok
     */
    public function search(array $data, Model_Project $project)
    {
        // Aktiv projektek
        $activeProjects         = $project->getActivesOrderedByCreated();

        // Szukites iparagakra
        $projectsIndustries     = $this->searchRelationsInProjects(
            $activeProjects,
            Arr::get($data, 'industries', []),
            new Model_Project_Industry()
        );

        // Szukites szakteruletekre
        $projectsProfessions    = $this->searchRelationsInProjects(
            $projectsIndustries,
            Arr::get($data, 'professions', []),
            new Model_Project_Profession()
        );

        // Szukites kepessegekre
        $projectsSkills         = $this->searchBySkills(
            $projectsProfessions,
            Arr::get($data, 'skills', []),
            Arr::get($data, 'skill_relation', 1)
        );

        return $projectsSkills;
    }

    /**
     * Keresi a kapott kapcsolatokat a kapott projektekben
     *
     * @param array $projects               Osszes projekt
     * @param array $searchedRelationIds    Keresett kapcsolat azonositok
     * @param ORM $relationModel            A keresett kapcsolat ures model -je, pl Model_Project_Skill
     *
     * @return array                        Azok a projektek, amikben megtalalhato legalabb egy a keresett azonositokbol
     */
    protected function searchRelationsInProjects(array $projects, array $searchedRelationIds, ORM $relationModel)
    {
        $result = [];

        // Nincs keresendo adat
        if (empty($searchedRelationIds)) {
            return $projects;
        }

        // Osszes projekthez tartozo osszes kapcsolat, pl osszes iparag
        $allRelationIds = $relationModel->getAll();

        foreach ($projects as $project) {
            $found = $this->searchRelationsInOneProject($project, $allRelationIds, $searchedRelationIds);

            if ($found) {
                $result[] = $project;
            }
        }

        return $result;
    }

    /**
     * A kapott projektben keresi kapcsolatokat
     *
     * @param Model_Project $project        Ebben a projektben keres
     * @param array $allRelationIds         Cache -bol lekerdezett osszes projekthez tartozo osszes kapcsolat azonosito.
     *                                      Igy, hogy az egesz tombot atadtjuk, csak egyszer kell lekerdezni, a fuggveny
     *                                      ben pedig kiszedjuk azokat, amik a $project -re vonatkoznak
     * @param array $searchedRelationIds    Keresett kapcsolat azonositok
     *
     * @return bool                         true, ha a keresett kapcsolatok kozul legalabb az egyik megtalalhato a projektben
     */
    protected function searchRelationsInOneProject(Model_Project $project, array $allRelationIds, array $searchedRelationIds)
    {
        // Adott projekt kapcsolatai
        $projectRelationIds = Arr::get($allRelationIds, $project->project_id, []);

        // Vegmegy a postban kapott, keresett kapcsolatokon. Ha barmelyik megtalalhato a projekt kapcsolatai kozt, true
        foreach ($searchedRelationIds as $searchedRelationId) {
            $found = $this->searchOneRelationInOneProject($searchedRelationId, $projectRelationIds);

            if ($found) {
                return true;
            }
        }

        return false;
    }

    /**
     * A kapott projekt kapcsolatai kozott keresi a kapott kapcsolat azonostitot
     *
     * @param $searchedRelationId           Keresett kapcsolat azonosito
     * @param array $projectRelationIds     Egy projekthez tartozo osszes kapcsolat azonosito
     *
     * @return bool                         true, ha a keresett kapcsolat megtalalhato a projekt kapcsolatai kozott
     */
    protected function searchOneRelationInOneProject($searchedRelationId, array $projectRelationIds)
    {
        // Ha a postbol kapott kapcsolat megtalalhato a projekt kapcsolatai kozott
        if (in_array($searchedRelationId, $projectRelationIds)) {
            return true;
        }

        return false;
    }

    /**
     * A kapott projekteket szukiti a kapott kepessegek alapjan.
     * skill_relation -tol fugg, hogy VAGY / ES kapcsolat van a keresett kepessegek kozott
     *
     * @param array $projects			Projektek (alapesetben a szakteruletekre szukitett projektek)
     * @param array $postSkills			POST kepessegek
     * @param int 	$skillRelation		Keresett kepessegek kapcsolata (ES / VAGY)
     *
     * @return array
     */
    protected function searchBySkills(array $projects, array $postSkills, $skillRelation = 1)
    {
        if (empty($postSkills))
        {
            return $projects;
        }

        $result         = [];
        $projectSkill   = new Model_Project_Skill();

        foreach ($projects as $project)
        {
            /**
             * @var $project Model_Project
             */

            $found = $this->searchBySkillsAndSkillRelation($project, $postSkills, $projectSkill, $skillRelation);

            if ($found)
            {
                $result[] = $project;
            }
        }

        return $result;
    }

    /**
     * Visszaadja, hogy a kapott projektben megtalalhatok -e a kapott kepessegek
     *
     * @param Model_Project $project		        Projekt
     * @param array $postSkills		                Keresett kepessegek
     * @param Model_Project_Skill $projectSkill     Ures model
     * @param int $relation                         ES / VAGY kapcsolat
     *
     * @return bool
     */
    protected function searchBySkillsAndSkillRelation(Model_Project $project, array $postSkills, Model_Project_Skill $projectSkill, $relation)
    {
        $cacheProjectsSkills    = $projectSkill->getAll();

        // Projekt kepessegei
        $projectSkills          = Arr::get($cacheProjectsSkills, $project->project_id, []);
        $has                    = ($relation == self::SKILL_RELATION_OR) ? false : true;

        // Ha nincs a projekthez kepesseg
        if (empty($projectSkills))
        {
            return true;
        }

        // Vegmegy a postban kapott, keresett kepessegeken
        foreach ($postSkills as $postSkillId)
        {
            switch ($relation)
            {
                case self::SKILL_RELATION_OR: default:
                    // Ha a postbol kapott kepsseg megtalalhato a projekt kepessegei kozott
                    if (in_array($postSkillId, $projectSkills))
                    {
                        $has = true;
                        break;
                    }
                    break;

                case self::SKILL_RELATION_AND:
                    // Ha a postbol kapott kepesseg NEM talalhato a projekt kepessegei kozott
                    if (!in_array($postSkillId, $projectSkills))
                    {
                        $has = false;
                    }
                    break;
            }
        }

        return $has;
    }
}