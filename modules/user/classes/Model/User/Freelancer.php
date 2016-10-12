<?php

class Model_User_Freelancer extends Model_User
{
    /**
     * @return int
     */
    public function getType()
    {
        return Model_User::TYPE_FREELANCER;
    }

    /**
     * @param array $post
     */
    public function addRelations(array $post)
    {
        parent::addRelations($post);

        $this->removeAll('users_skills', 'user_id');
        $this->removeAll('users_profiles', 'user_id');

        $this->addRelation($post, new Model_User_Skill(), new Model_Skill());

        $this->addProfiles($post, new Model_Profile());
    }
}