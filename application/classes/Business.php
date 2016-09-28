<?php

class Business
{
    /**
     * @var ORM
     */
    private $_model;

    public static function getIdsFromModelsMulti(array $models, $primaryKey = null)
    {
        $ids = [];
        foreach ($models as $i => $item) {
            if (!isset($ids[$i])) {
                $ids[$i] = [];
            }

            foreach ($item as $model) {
                $ids[$i][] = self::checkAndGetIdFromModel($model, $primaryKey);
            }
        }

        return $ids;
    }

    public static function checkAndGetIdFromModel($model, $primaryKey = null)
    {
        if ($model instanceof ORM) {
            if ($primaryKey) {
                return $model->{$primaryKey};
            }

            return $model->pk();
        }
    }
}