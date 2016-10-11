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
        $industryItem = new Search_View_Container_Relation_Item_Project(
            Arr::get($data, 'industries', []), Search_View_Container_Relation_Item::TYPE_INDUSTRY);

        $professionItem = new Search_View_Container_Relation_Item_Project(
            Arr::get($data, 'professions', []), Search_View_Container_Relation_Item::TYPE_PROFESSION);

        $skillItem = new Search_View_Container_Relation_Item_Project(
            Arr::get($data, 'skills', []), Search_View_Container_Relation_Item::TYPE_SKILL);

        $skillItem->setRelation(Arr::get($data, 'skill_relation', 1));

        $relationContainer = new Search_View_Container_Relation($industryItem, $professionItem, $skillItem);

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