<?php

abstract class Model_User_Relation extends Model_Relation
{
    /**
     * @return Model_User_Relation
     */
    abstract protected function createModel();

    /**
     * @param string $value
     * @return ORM
     */
    public function getOrCreateBy($value)
    {
        $model   = $this->getEndRelationModel();

        if (Text::isId($value)) {
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
     * @param array $ids
     */
    public function createBy(array $ids)
    {
        foreach ($ids as $id) {
            $model                                          = $this->getOrCreateBy($id);
            $relation                                       = $this->createModel();
            $relation->user_id                              = Auth::instance()->get_user()->user_id;
            $relation->{$this->getPrimaryKeyForEndModel()}  = intval($model->{$this->getPrimaryKeyForEndModel()});

            $relation->save();
        }
    }
}