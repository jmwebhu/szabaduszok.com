<?php

class Search_Complex_User extends Search_Complex
{
    /**
     * @return Model_User
     */
    public function createSearchModel()
    {
        return new Model_User();
    }

    /**
     * @return Array_Builder
     */
    public function getInitModels()
    {
        $withPicture	= AB::select()->from($this->createSearchModel())->where('profile_picture_path', '!=', '')->and_where('type', '=', 1)->order_by('lastname')->execute()->as_array();
        $withoutPicture	= AB::select()->from($this->createSearchModel())->where('profile_picture_path', '=', '')->and_where('type', '=', 1)->order_by('lastname')->execute()->as_array();
        $merged			= Arr::merge($withPicture, $withoutPicture);

        return AB::select()->from($merged)->where('user_id', '!=', '');
    }

    /**
     * @return Search_Relation
     */
    protected function makeSearchRelation()
    {
        return Search_Relation_Factory_User::makeSearch($this);
    }

    /**
     * @return string
     */
    public function getModelPrimaryKey()
    {
        return 'user_id';
    }

    /**
     * @return Model_User_Industry
     */
    protected function getIndustryRelationModel()
    {
        return new Model_User_Industry();
    }

    /**
     * @return Model_User_Profession
     */
    protected function getProfessionRelationModel()
    {
        return new Model_User_Profession();
    }

    /**
     * @return Model_User_Skill
     */
    protected function getSkillRelationModel()
    {
        return new Model_User_Skill();
    }


}