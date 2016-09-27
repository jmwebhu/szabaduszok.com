<?php

class Business
{
    /**
     * @var ORM
     */
    private $_model;

    public static function getIdsFromModelsMulti(array $models)
    {
        $ids = [];
        foreach ($models as $i => $item) {
            if (!isset($ids[$i])) {
                $ids[$i] = [];
            }

            foreach ($item as $model) {
                $ids[$i][] = self::checkAndGetIdFromModel($model);
            }
        }

        return $ids;
    }

    public static function checkAndGetIdFromModel($model)
    {
        if ($model instanceof ORM && $model->loaded()) {
            return $model->pk();
        }
    }
}