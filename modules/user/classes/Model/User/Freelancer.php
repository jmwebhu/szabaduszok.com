<?php

class Model_User_Freelancer extends Model_User_Abstract
{
    private $_submitError = false;

    /**
     * @return array
     */
    public function rules()
    {
        $parent = parent::rules();
        $freelancer = [
            'min_net_hourly_wage'   => [['not_empty'], ['numeric']],
            'webpage'               => [['url']]            
        ];

        return array_merge($parent, $freelancer);
    }

    /**
     * @return Array_Builder
     */
    public function baseSelect()
    {
        $base = parent::baseSelect();
        return $base->where('type', '=', $this->getType());
    }

    public function submit(array $data)
    {
        parent::submit($data);
        if (!$this->_submitError) {
            $this->saveProjectNotification($data);
        }
    }

    /**
     * @param array $post
     * @return array
     */
    public function saveProjectNotification(array $post)
    {
        $this->removeOldProjectNotifications();
        $this->addNewProjectNotifications($post);

        $this->skill_relation = Arr::get($post, 'skill_relation', 1);
        $this->save();

        $this->updateSession();

        return ['error' => false];
    }

    protected function removeOldProjectNotifications()
    {
        $this->removeAll('users_project_notification_industries', $this->_primary_key);
        $this->removeAll('users_project_notification_professions', $this->_primary_key);
        $this->removeAll('users_project_notification_skills', $this->_primary_key);
    }

    /**
     * @param array $post
     */
    protected function addNewProjectNotifications(array $post)
    {
        ORM::factory('User_Project_Notification_Industry')->createBy(Arr::get($post, 'industries', []));
        ORM::factory('User_Project_Notification_Profession')->createBy(Arr::get($post, 'professions', []));
        ORM::factory('User_Project_Notification_Skill')->createBy(Arr::get($post, 'skills', []));
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
     * @throws Exception
     */
    public function addRelations(array $post)
    {
        try {
            parent::addRelations($post);

            $this->removeAll('users_skills', 'user_id');
            $this->removeAll('users_profiles', 'user_id');

            $this->addRelation($post, new Model_User_Skill(), new Model_Skill());
            $this->addProfiles($post, new Model_Profile());

        } catch (Exception $ex) {
            $this->_submitError = true;

            $this->removeAll('users_industries', 'user_id');
            $this->removeAll('users_professions', 'user_id');
            $this->removeAll('users_skills', 'user_id');
            Cache::instance()->delete_all();
        }
    }

    /**
     * @return bool
     */
    public function hasProjectNotification()
    {
        $notifications = $this->project_notifications->find_all();
        return !empty($notifications);
    }

    /**
     *
     * @param array			$post
     * @param Model_Profile	$profileModel
     *
     */
    protected function addProfiles(array $post, Model_Profile $profileModel)
    {
        $profiles		= $profileModel->where('is_active', '=', 1)->find_all();
        $baseUrls		= [];

        foreach ($profiles as $profile) {
            $baseUrls[$profile->pk()] = $profile->base_url;
        }

        foreach (Arr::get($post, 'profiles', []) as $url) {
            $fixedUrl   = Text_User::fixUrl(['url' => $url], 'url')['url'];

            foreach ($baseUrls as $profileId => $baseUrl) {
                if (stripos($fixedUrl, $baseUrl) !== false) {
                    $userProfile				= new Model_User_Profile();
                    $userProfile->profile_id	= $profileId;
                    $userProfile->url			= $fixedUrl;
                    $userProfile->user_id		= $this->pk();

                    $userProfile->save();
                }
            }
        }
    }

    /**
     * @param Model_User_Profile $userProfile
     * @return array
     */
    public function getProfileUrls(Model_User_Profile $userProfile)
    {
        $data			= [];
        $userProfiles	= $userProfile->where('user_id', '=', $this->pk())->find_all();

        foreach ($userProfiles as $profile) {
            $data[$profile->profile->pk()] = $profile->url;
        }

        return $data;
    }

    /**
     * @param $slug
     * @return Model_User_Freelancer
     */
    public function getBySlug($slug)
    {
        $model = parent::getBySlug($slug);
        return new Model_User_Freelancer($model->user_id);
    }

    /**
     * @param Model_Project $project
     * @return bool
     */
    public function isCandidateIn(Model_Project $project)
    {
        return Model_Project_Partner::isUserCandidateInProject($this, $project);
    }

    /**
     * @param Model_Project $project
     * @return bool
     */
    public function isParticipantIn(Model_Project $project)
    {
        return Model_Project_Partner::isUserParticipateInProject($this, $project);
    }
}