<?php

class Model_User_Freelancer extends Model_User_Abstract
{
    public function submit(array $data)
    {
        parent::submit($data);
        $this->saveProjectNotification($data);
    }

    /**
     * @param array $post
     * @return array
     */
    public function saveProjectNotification(array $post)
    {
        $this->removeAll('users_project_notification', $this->_primary_key);

        $industries     = Arr::get($post, 'industries', []);
        $professions    = Arr::get($post, 'professions', []);
        $skills         = Arr::get($post, 'skills', []);
        $skillRelation  = Arr::get($post, 'skill_relation', 1);

        $this->saveProjectNotificationIndustries($industries);
        $this->saveProjectNotificationByType('profession', $professions);
        $this->saveProjectNotificationByType('skill', $skills);

        $this->skill_relation = $skillRelation;
        $this->save();

        return ['error' => false];
    }

    /**
     * @param array $industries
     */
    private function saveProjectNotificationIndustries(array $industries)
    {
        foreach ($industries as $industryId) {
            $notificationIndustry = new Model_User_Project_Notification();

            $notificationIndustry->industry_id = $industryId;
            $notificationIndustry->user_id = $this->user_id;

            $notificationIndustry->save();
        }
    }

    /**
     * @param string $type
     * @param array $relationIds
     */
    private function saveProjectNotificationByType($type, array $relationIds)
    {
        $foreignKey = $this->getForeignKeyByProjectNotificationType($type);

        foreach ($relationIds as $relationId) {
            $relation = $this->getOrCreateRelation($relationId, $type, false);
            $notificationProfession = new Model_User_Project_Notification();

            $notificationProfession->{$foreignKey} = $relation->{$foreignKey};
            $notificationProfession->user_id = $this->user_id;

            $notificationProfession->save();
        }
    }

    /**
     * @param string $type
     * @return string
     */
    private function getForeignKeyByProjectNotificationType($type)
    {
        switch ($type) {
            case 'profession':
                return 'profession_id';
                break;

            case 'skill':
                return 'skill_id';
                break;
        }

        return 'profession_id';
    }

    /**
     * @return int
     */
    public function getType()
    {
        return Entity_User::TYPE_FREELANCER;
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