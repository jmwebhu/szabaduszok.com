<?php

class Search_View_Container_Factory_Project
{
    /**
     * @param array $data
     * @return Search_View_Container_Project
     */
    public static function createContainer(array $data)
    {
        if ($data['current'] == 'complex') {
            return self::createComplex($data);
        } else {
            return self::createSimple($data);
        }
    }

    /**
     * @param array $data
     * @return Search_View_Container_Project
     */
    private static function createComplex(array $data)
    {
        $relationContainer = new Search_View_Container_Relation();

        $industries = Arr::get($data, 'industries', []);
        foreach ($industries as $industry) {
            $industryItem = new Search_View_Container_Relation_Item_Project(
                $industry, Search_View_Container_Relation_Item::TYPE_INDUSTRY);

            $relationContainer->addItem($industryItem, Search_View_Container_Relation_Item::TYPE_INDUSTRY);
        }

        $professions = Arr::get($data, 'professions', []);
        foreach ($professions as $profession) {
            $professionItem = new Search_View_Container_Relation_Item_Project(
                $profession, Search_View_Container_Relation_Item::TYPE_PROFESSION);

            $relationContainer->addItem($professionItem, Search_View_Container_Relation_Item::TYPE_PROFESSION);
        }

        $skills = Arr::get($data, 'skills', []);
        foreach ($skills as $skill) {
            $skillItem = new Search_View_Container_Relation_Item_Project(
                $skill, Search_View_Container_Relation_Item::TYPE_SKILL);

            $relationContainer->addItem($skillItem, Search_View_Container_Relation_Item::TYPE_SKILL);
        }

        $relationContainer->setSkillRelation(Arr::get($data, 'skill_relation', 1));

        $container = new Search_View_Container_Project(Arr::get($data, 'current'));
        $container->setRelationContainer($relationContainer);

        return $container;
    }

    /**
     * @param array $data
     * @return Search_View_Container_Project
     */
    private static function createSimple(array $data)
    {
        $container = new Search_View_Container_Project(Arr::get($data, 'current'));
        $container->setSearchTerm(Arr::get($data, 'search_term'));

        return $container;
    }
}