<?php

class Business
{
    /**
     * @var ORM
     */
    protected $_model;

    /**
     * @param ORM $model
     */
    public function __construct(ORM $model)
    {
        $this->_model = $model;
    }

    /**
     * @param array $models
     * @param null|string $primaryKey
     * @return array
     */
    public static function getIdsFromModelsSingle(array $models, $primaryKey = null)
    {
        $ids = [];

        try {
            foreach ($models as $model) {
                self::checkModel($model);
                $ids[] = self::getIdFromModel($model, $primaryKey);
            }

        } catch (Exception $ex) {
            Log::instance()->addException($ex);

            return [];
        }

        return $ids;
    }

    /**
     * @param array $models
     * @param null|string $primaryKey
     * @return array
     */
    public static function getIdsFromModelsMulti(array $models, $primaryKey = null)
    {
        $ids = [];
        foreach ($models as $i => $array) {
            if (!is_array($array)) {
                $ids[$i] = [];
                continue;
            }

            $ids[$i] = self::getIdsFromModelsSingle($array, $primaryKey);
        }

        return $ids;
    }

    /**
     * @param mixed $model
     * @return bool
     * @throws Exception
     */
    protected static function checkModel($model)
    {
        if (!$model instanceof ORM) {
            throw new Exception('Instead of ORM, ' . Variable::getTypeOf($model) . ' was given');
        }

        return true;
    }

    /**
     * @param ORM $model
     * @param null|string $primaryKey
     * @return mixed
     */
    protected static function getIdFromModel(ORM $model, $primaryKey = null)
    {
        if ($primaryKey && self::checkPrimaryKey($model, $primaryKey)) {
            return $model->{$primaryKey};
        }

        return $model->{$model->primary_key()};
    }

    /**
     * @param ORM $model
     * @param string $primaryKey
     * @return bool
     * @throws Exception
     */
    protected static function checkPrimaryKey(ORM $model, $primaryKey)
    {
        if ($model->primary_key() != $primaryKey ) {
            throw new Exception('Invalid primary key: ' . $primaryKey . ' for object type of ' . Variable::getTypeOf($model));
        }

        return true;
    }
}