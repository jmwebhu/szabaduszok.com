<?php

class Business
{
    /**
     * @var ORM
     */
    private $_model;

    public static function getIdsFromModels(array $models)
    {
        $ids = [];
        foreach ($models as $i => $item) {
            if (is_array($item)) {
                foreach ($item as $model) {
                    if (!isset($ids[$i])) {
                        $ids[$i] = [];
                    }

                    $ids[$i][] = self::checkAndGetIdFromModel($model);
                }
            } else {
                $ids[$i][] = self::checkAndGetIdFromModel($item);
            }
        }

        return $ids;
    }

    public static function checkAndGetIdFromModel(ORM $model)
    {
        if ($model instanceof ORM && $model->loaded()) {
            return $model->pk();
        }
    }
}