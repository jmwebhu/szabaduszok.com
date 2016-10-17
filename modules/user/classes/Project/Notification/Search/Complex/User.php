<?php

class Project_Notification_Search_Complex_User extends Search_Complex
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
        return AB::select()->from($this->createSearchModel())->where('type', '=', Entity_User::TYPE_FREELANCER)->order_by('lastname')->execute()->as_array();
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