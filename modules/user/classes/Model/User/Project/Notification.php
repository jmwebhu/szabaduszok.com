<?php

abstract class Model_User_Project_Notification extends Model_Relation
{
    /**
     * @return Model_User_Project_Notification
     */
    abstract protected function createModel();

    /**
     * @param string $value
     * @return ORM
     */
    public function getOrCreateBy($value)
    {
        $model   = $this->getEndRelationModel();

        if (is_numeric($value)) {
            $intval             = intval($value);
            $model              = $model->where($model->primary_key(), '=', $intval)->find();

        } else {
            $model		        = $model->where('name', '=', mb_strtolower($value))->find();

            if ($model->loaded()) {
                return $model;
            }

            $model->name = $value;
            $model->save();

            $model->saveSlug();
            $model->cacheToCollection();
        }

        return $model;
    }

    /**
     * @param array $values
     */
    public function createBy(array $values)
    {
        $uniqueValues = Arr::uniqueString($values);
        $clearedValues = Arr::removeEmptyValues($uniqueValues);

        foreach ($clearedValues as $value) {
            $model                                          = $this->getOrCreateBy($value);

            if (empty(intval($model->{$this->getPrimaryKeyForEndModel()}))) continue;

            $relation                                       = $this->createModel();
            $relation->user_id                              = Auth::instance()->get_user()->user_id;
            $relation->{$this->getPrimaryKeyForEndModel()}  = intval($model->{$this->getPrimaryKeyForEndModel()});
            $save = $relation->save();        
        }
    }

    protected function isExists($userId, $relation, $primaryKeyColumn, $primaryKeyValue)
    {
        $existing = $relation
            ->where('user_id', '=', $userId)
            ->and_where($primaryKeyColumn, '=', $primaryKeyValue)
            ->find();

        return $existing->loaded();
    }
}
