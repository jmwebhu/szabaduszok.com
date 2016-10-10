<?php

abstract class Model_Relation extends ORM
{
    /**
     * Kapcsolat idegenkulcs neve, pl.: 'project_id'
     * @var string
     */
    protected $_relationFk;

    /**
     * A kapcsolat vegpondjanak elsodleges kulcsat adja vissza. Pl.: Model_Project_Industry eseten a
     * Model_Industry elsodleges kulcsat, industry_id
     *
     * unittestben nem mukodik az ORM->pk()
     * @return string
     */
    abstract public function getPrimaryKeyForEndModel();

    /**
     * Visszaadja a kapcsolat vegpontjahoz tartozo ORM objektumot.
     * Pl.: Model_Project_Industry eseten visszaad egy Model_Industry peldanyt
     *
     * @return ORM
     */
    abstract public function getEndRelationModel();

    /**
     * Kereskor hasznalt tomb.
     * Pl.: Model_Project_Industry visszzadja, hogy '_searchedIndustryIds'
     * @return string
     */
    abstract public function getSearchedRelationIdsPropertyName();

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
            if (!isset($collection[$model->{$this->_relationFk}])) {
                $collection[$model->{$this->_relationFk}] = [];
            }

            $relationName = $this->getRelationNameFromClassName();
            $collection[$model->{$this->_relationFk}][] = $model->{$relationName};
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