<?php

/**
 * Class Model_Project_Relation
 *
 * Projekt kapcsolatainak alaposztalya
 */

abstract class Model_Project_Relation extends ORM
{
    public function cacheAll()
    {
        $cache = Cache::instance();
        $cache->delete($this->_table_name);

        $orm = ORM::factory($this->_object_name);
        $models = $orm->find_all();

        $collection = [];

        foreach ($models as $model) {
            if (!isset($collection[$model->project_id])) {
                $collection[$model->project_id] = [];
            }

            $collection[$model->project_id][] = $model->industry;
        }

        $cache->set($this->_table_name, $collection);

        return $collection;
    }

    public function getAll()
    {
        $cache = Cache::instance();
        $collection = $cache->get($this->_table_name);

        if (!$collection) {
            $orm = ORM::factory($this->_object_name);
            $collection = $orm->cacheAll();
        }

        return $collection;
    }
}