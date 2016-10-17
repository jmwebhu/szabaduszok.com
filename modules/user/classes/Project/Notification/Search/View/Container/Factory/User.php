<?php

class Project_Notification_Search_View_Container_Factory_User
{
    /**
     * @param array $data
     * @return Search_View_Container_User
     */
    public static function createContainer(array $data)
    {
        $relationContainer      = new Search_View_Container_Relation_User();
        $industries             = Arr::get($data, 'industries', []);
        $selectedIndustryIds    = Arr::get($data, 'selectedIndustryIds', []);

        foreach ($industries as $industry) {
            $selected = (in_array($industry->industry_id, $selectedIndustryIds));

            $industryItem = new Search_View_Container_Relation_Item(
                $industry, Search_View_Container_Relation_Item::TYPE_INDUSTRY, $selected);

            $relationContainer->addItem($industryItem, Search_View_Container_Relation_Item::TYPE_INDUSTRY);
        }

        self::addItems(Arr::get($data, 'professions', []), Search_View_Container_Relation_Item::TYPE_PROFESSION);
        self::addItems(Arr::get($data, 'skills', []), Search_View_Container_Relation_Item::TYPE_SKILL);

        $relationContainer->setSkillRelation(Arr::get($data, 'skill_relation', 1));

        $container = new Project_Notification_Search_View_Container_User('complex');
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