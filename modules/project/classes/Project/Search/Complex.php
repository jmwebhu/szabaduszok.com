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
        $projectsActive	= AB::select()->from('projects')->where('is_active', '=', 1)->order_by('created_at', 'DESC')->execute()->as_array();

        // Szukites iparagakra
        $projectsIndustries     = $this->searchByRelation(
            $projectsActive,
            Arr::get($data, 'industries', []),
            new Model_Project_Industry()
        );

        // Szukites szakteruletekre
        $projectsProfessions    = $this->searchByRelation(
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
     * Projekt keresese adott tipusu kapcsolat alapjan. Ez lehet Profession, vagy Industry
     *
     * @param array $projects           Projektek
     * @param array $postRelations      POST kapcsolatok azonositoi
     * @param ORM $relationModel        Keresett kapcsolat ures modelje pl Model_Project_Skill
     *
     * @return array                    Talalati lista
     */
    protected function searchByRelation(array $projects, array $postRelations, ORM $relationModel)
    {
        $result = [];

        // Nincs keresendo adat
        if (empty($postRelations))
        {
            return $projects;
        }

        $cacheRelations = $relationModel->getAll();

        foreach ($projects as $project)
        {
            /**
             * @var $project Model_Project
             */

            // Projekt szakteruletei
            $projectRelations = Arr::get($cacheRelations, $project->project_id, []);

            // Vegmegy a postban kapott, keresett kapcsolatokon. Ha barmelyik egyezik, beleteszi $result -ba a projektet
            foreach ($postRelations as $postRelationId)
            {
                // Ha a postbol kapott kapcsolat megtalalhato a projekt kapcsolatai kozott
                if (in_array($postRelationId, $projectRelations))
                {
                    $result[] = $project;
                    break;
                }
            }
        }

        return $result;
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