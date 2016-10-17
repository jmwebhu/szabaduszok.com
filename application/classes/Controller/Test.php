<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $post = [
            'industries'    => [36],
            'professions'     => ['xy', 181],
            'skills'     => ['classfm1', 355],
        ];

        ORM::factory('User_Project_Notification_Industry')->createBy(Arr::get($post, 'industries', []));
        ORM::factory('User_Project_Notification_Profession')->createBy(Arr::get($post, 'professions', []));
        ORM::factory('User_Project_Notification_Skill')->createBy(Arr::get($post, 'skills', []));
    }	

    protected function getModel()
    {
        return new Model_Industry();
    }
}
