<?php

class Business_Project extends Business
{
    /**
     * @param ORM $relation
     * @return string
     */
    public static function getRelationIdField(ORM $relation)
    {
        if ($relation instanceof Model_Project_Industry) {
            return 'industry_id';
        } elseif ($relation instanceof Model_Project_Profession) {
            return 'profession_id';
        } elseif ($relation instanceof Model_Project_Skill) {
            return 'skill_id';
        }

        return 'industry_id';
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
        if ($index != ($count - 1)) {
            return ' ';
        }

        return '';
    }

    /**
     * @param int $maxChars
     * @return string
     */
    public function getShortDescriptionCutOffAt($maxChars = 100)
    {
        return (strlen($this->_model->short_description) > $maxChars)
            ? mb_substr($this->_model->short_description, 0, $maxChars) . '...'
            : $this->_model->short_description;
    }

    /**
     * @param int $maxChars
     * @return string
     */
    public function getNameCutOffAt($maxChars = 70)
    {
        return (strlen($this->_model->name) > $maxChars)
            ? mb_substr($this->_model->name, 0, $maxChars) . '...'
            : $this->_model->name;
    }
}