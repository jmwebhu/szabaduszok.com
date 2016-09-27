<?php

class Model_Project_Industry extends ORM
{
    protected $_table_name  = 'projects_industries';
    protected $_primary_key = 'id';

    protected $_belongs_to = [
        'project' => [
            'model'         => 'Project',
            'foreign_key'   => 'project_id'
        ],
        'industry' => [
            'model'         => 'Industry',
            'foreign_key'   => 'industry_id'
        ],
    ];

    protected $_table_columns = [
        'id'            => ['type' => 'int', 'key' => 'PRI'],
        'project_id'	=> ['type' => 'int', 'null' => true],
        'industry_id'	=> ['type' => 'int', 'null' => true],
    ];

    public function cacheAll()
    {
        $cache = Cache::instance();
        $cache->delete($this->_table_name);

        $orm = ORM::factory($this->_object_name);
        $models = $orm->find_all();

        $collection = [];

        foreach ($models as $model)
        {
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

        if (!$collection)
        {
            $orm = ORM::factory($this->_object_name);
            $collection = $orm->cacheAll();
        }

        return $collection;
    }
}