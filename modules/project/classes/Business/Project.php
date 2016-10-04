<?php

class Business_Project extends Business
{
    /**
     * @param ORM $relation
     * @return string
     */
    public static function getRelationIdField(ORM $relation)
    {
        $model = self::getRelationEndModel($relation);
        return $model->primary_key();
    }

    /**
     * @param ORM $relationModel
     * @return ORM
     */
    protected static function getRelationEndModel(ORM $relationModel)
    {
        $className          = get_class($relationModel);
        $parts              = explode('_', $className);
        $endClassNamePart   = Arr::get($parts, count($parts) - 1, 'Industry');
        $endClass           = 'Model_' . $endClassNamePart;

        return new $endClass();
    }

    /**
     * @return string
     */
    public function getSearchTextFromFields()
    {
        $sb = SB::create($this->getBaseSearchText());
        $sb->append($this->getUserSearchText());
        $sb->append($this->getRelationsSearchText());

        return $sb->get();
    }

    /**
     * @return string
     */
    protected function getBaseSearchText()
    {
        return SB::create($this->_model->name)->append(' ')
            ->append($this->_model->short_description)->append(' ')
            ->append($this->_model->long_description)->append(' ')
            ->append($this->_model->email)->append(' ')
            ->append($this->_model->phonenumber)->append(' ')
            ->append(date('Y-m-d'))->append(' ')->get();
    }

    /**
     * @return string
     */
    protected function getUserSearchText()
    {
        if (!$this->_model->user->loaded()) {
            return '';
        }

        $sb = SB::create()->append($this->_model->user->name())->append(' ')
            ->append($this->_model->user->address_city)->append(' ');

        if ($this->_model->user->is_company) {
            $sb->append($this->_model->user->company_name)->append(' ');
        }

        return $sb->get();
    }

    /**
     * @return string
     */
    protected function getRelationsSearchText()
    {
        $relations = ['industries', 'professions', 'skills'];
        $sb = SB::create();

        foreach ($relations as $i => $relation) {
            $relationString = $this->_model->getRelationString($relation);
            $sb->append($relationString);

            if ($relationString) {
                $sb->append($this->getSpaceIfIndexNotEqualsCount($i, count($relations)));
            }
        }

        return $sb->get();
    }

    /**
     * @param int $index
     * @param int $count
     * @return string
     */
    protected function getSpaceIfIndexNotEqualsCount($index, $count)
    {
        if ($index == ($count - 1)) {
            return '';
        }

        return ' ';
    }

    /**
     * @param string $field
     * @param int $maxChars
     * @return mixed|string
     */
    public function getFieldCutOffAt($field, $maxChars = 75)
    {
        if (strlen($this->_model->{$field}) > $maxChars) {
            return mb_substr($this->_model->{$field}, 0, $maxChars) . '...';
        }

        return $this->_model->{$field};
    }
}