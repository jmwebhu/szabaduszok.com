<?php

class Business_Project extends Business
{
    public static function getRelationIdField(ORM $relation)
    {
        if ($relation instanceof Model_Project_Industry) {
            return 'industry_id';
        } elseif ($relation instanceof Model_Project_Profession) {
            return 'profession_id';
        } elseif ($relation instanceof Model_Project_Skill) {
            return 'skill_id';
        }
    }

    public function getSearchText()
    {
        $sb = SB::create($this->_model->name)->append(' ')
            ->append($this->_model->short_description)->append(' ')
            ->append($this->_model->long_description)->append(' ')
            ->append($this->_model->email)->append(' ')
            ->append($this->_model->phonenumber)->append(' ')
            ->append(date('Y-m-d'))->append(' ');

        $user = new Model_User($this->_model->user_id);

        if ($user->loaded()) {
            $sb->append($this->_model->user->name())->append(' ')
                ->append($this->_model->user->address_city)->append(' ');

            if ($this->_model->user->is_company) {
                $sb->append($this->_model->user->company_name)->append(' ');
            }
        }

        $relations = ['industries', 'professions', 'skills'];

        foreach ($relations as $relation) {
            $text = $this->_model->getRelationString($relation);
            $sb->append($text)->append(' ');
        }

        return $sb->get();
    }

    public function getShortDescriptionCutOffAt($maxChars = 100)
    {
        return (strlen($this->_model->short_description) > $maxChars)
            ? mb_substr($this->_model->short_description, 0, $maxChars) . '...'
            : $this->_model->short_description;
    }

    public function getNameCutOffAt($maxChars = 70)
    {
        return (strlen($this->_model->name) > $maxChars)
            ? mb_substr($this->_model->name, 0, $maxChars) . '...'
            : $this->_model->name;
    }
}