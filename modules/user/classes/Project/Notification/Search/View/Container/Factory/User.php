<?php

class Project_Notification_Search_View_Container_Factory_User
{
    /**
     * @var Search_View_Container
     */
    private static $_relationContainer;

    /**
     * @param array $data
     * @return Project_Notification_Search_View_Container_User
     */
    public static function createContainer(array $data)
    {
        self::$_relationContainer   = new Project_Notification_Search_View_Container_Relation_User();
        $industries                 = Arr::get($data, 'industries', []);

        foreach ($industries as $industry) {
            $industryItem = new Search_View_Container_Relation_Item(
                Cache::instance()->get('industries')[$industry['id']],
                Search_View_Container_Relation_Item::TYPE_INDUSTRY,
                $industry['selected']);

            self::$_relationContainer->addItem($industryItem, Search_View_Container_Relation_Item::TYPE_INDUSTRY);
        }

        self::addItems(Arr::get($data, 'professions', []), Search_View_Container_Relation_Item::TYPE_PROFESSION);
        self::addItems(Arr::get($data, 'skills', []), Search_View_Container_Relation_Item::TYPE_SKILL);

        self::$_relationContainer->setSkillRelation(Auth::instance()->get_user()->skill_relation);

        $container = new Project_Notification_Search_View_Container_User('complex');
        $container->setRelationContainer(self::$_relationContainer);

        return $container;
    }

    /**
     * @param array $items
     * @param int $type
     */
    private static function addItems(array $items, $type)
    {
        $name = ($type == Search_View_Container_Relation_Item::TYPE_PROFESSION) ? 'Profession' : 'Skill';
        $class = 'Model_' . ucfirst($name);

        foreach ($items as $item) {
            $itemModel = Cache::instance()->get(lcfirst($name) . 's')[$item['id']];

            if (!$itemModel || !$itemModel->loaded()) {
                $itemModel = new $class($item['id']);
            }

            $containerItem = new Search_View_Container_Relation_Item(
                $itemModel, $type, !empty($item['selected']));

            self::$_relationContainer->addItem($containerItem, $type);
        }
    }
}