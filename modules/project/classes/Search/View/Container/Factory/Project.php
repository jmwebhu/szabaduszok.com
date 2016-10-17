<?php

class Search_View_Container_Factory_Project
{
    /**
     * @var Search_View_Container_Relation_Project
     */
    private static $_relationContainer;

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
        self::$_relationContainer   = new Search_View_Container_Relation_Project();
        $industries                 = Arr::get($data, 'industries', []);
        $selectedIndustryIds        = Arr::get($data, 'selectedIndustryIds', []);

        foreach ($industries as $industry) {
            $selected = (in_array($industry->industry_id, $selectedIndustryIds));

            $industryItem = new Search_View_Container_Relation_Item(
                $industry, Search_View_Container_Relation_Item::TYPE_INDUSTRY, $selected);

            self::$_relationContainer->addItem($industryItem, Search_View_Container_Relation_Item::TYPE_INDUSTRY);
        }

        self::addItems(Arr::get($data, 'professions', []), Search_View_Container_Relation_Item::TYPE_PROFESSION);
        self::addItems(Arr::get($data, 'skills', []), Search_View_Container_Relation_Item::TYPE_SKILL);

        self::$_relationContainer->setSkillRelation(Arr::get($data, 'skill_relation', 1));

        $container = new Search_View_Container_Project('complex');
        $container->setRelationContainer(self::$_relationContainer);

        return $container;
    }

    /**
     * @param array $data
     * @return Search_View_Container_Project
     */
    private static function createSimple(array $data)
    {
        $container = new Search_View_Container_Project('simple');
        $container->setSearchTerm(Arr::get($data, 'search_term'));

        $relationContainer  = new Search_View_Container_Relation_Project();
        $industryModel      = new Model_Industry();
        $industries         = $industryModel->getAll();

        foreach ($industries as $industry) {
            $industryItem = new Search_View_Container_Relation_Item(
                $industry, Search_View_Container_Relation_Item::TYPE_INDUSTRY, false);

            $relationContainer->addItem($industryItem, Search_View_Container_Relation_Item::TYPE_INDUSTRY);
        }

        $container->setRelationContainer($relationContainer);

        return $container;
    }

    /**
     * @param array $models
     * @param int $type
     */
    private static function addItems(array $models, $type)
    {
        foreach ($models as $model) {
            $item = new Search_View_Container_Relation_Item(
                $model, $type, true);

            self::$_relationContainer->addItem($item, $type);
        }
    }
}