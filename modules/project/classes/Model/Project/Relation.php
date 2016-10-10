<?php

/**
 * Class Model_Project_Relation
 *
 * Projekt kapcsolatainak alaposztalya
 */

abstract class Model_Project_Relation extends ORM
{
    /**
     * Visszaadja a kapcsolat vegpontjahoz tartozo ORM objektumot.
     * Pl.: Model_Project_Industry eseten visszaad egy Model_Industry peldanyt
     *
     * @return Model_Project_Relation
     */
    abstract public function getEndRelationModel();

    /**
     * @return array
     */
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

            $relationName = $this->getRelationNameFromClassName();
            $collection[$model->project_id][] = $model->{$relationName};
        }

        $cache->set($this->_table_name, $collection);

        return $collection;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $cache      = Cache::instance();
        $collection = $cache->get($this->_table_name);

        $collection = $this->reCacheAndGetIfEmpty($collection);

        return $collection;
    }

    /**
     * @param array|null $collection
     * @return array
     */
    protected function reCacheAndGetIfEmpty($collection)
    {
        if (!$collection) {
            $orm    = ORM::factory($this->_object_name);
            $cache  = $orm->cacheAll();

            return $cache;
        }

        return $collection;
    }

    /**
     * @return string
     */
    protected function getRelationNameFromClassName()
    {
        $className  = get_class($this);
        $parts      = explode('_', $className);

        return strtolower(Arr::get($parts, 2, ''));
    }
}