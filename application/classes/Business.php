<?php

class Business
{
    /**
     * @var ORM
     */
    private $_model;

    public static function getIdsFromModelsSingle(array $models, $primaryKey = null)
    {
        $ids = [];
        foreach ($models as $model) {

            try {
                self::checkModel($model);
                $ids[] = self::getIdFromModel($model, $primaryKey);

            } catch (Exception $ex) {
                Log::instance()->add(Log::ERROR, $ex->getMessage() . ' Trace: ' . $ex->getTraceAsString());

                return [];
            }
        }

        return $ids;
    }

    public static function getIdsFromModelsMulti(array $models, $primaryKey = null)
    {
        $ids = [];
        foreach ($models as $i => $array) {
            if (!is_array($array)) {
                return [];
            }

            $ids[$i] = self::getIdsFromModelsSingle($array, $primaryKey);
        }

        return $ids;
    }

    protected static function checkModel($model)
    {
        if (!$model instanceof ORM) {
            throw new Exception('Instead of ORM, ' . get_class($model) . ' was given');
        }

        return true;
    }

    protected static function getIdFromModel(ORM $model, $primaryKey = null)
    {
        if ($primaryKey && self::checkPrimaryKey($model, $primaryKey)) {
            return $model->{$primaryKey};
        }

        return $model->pk();
    }

    protected static function checkPrimaryKey(ORM $model, $primaryKey)
    {
        $object = $model->object();

        if (!Arr::get($object, $primaryKey)) {
            throw new Exception('Trying to get non-existing property ' . $primaryKey . ' in class ' . get_class($model));
        }

        return true;
    }
}