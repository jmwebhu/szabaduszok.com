<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $data = [
            'user_id'       => 2020,
            'project_id'    => 10023,
            'extra_data'    => [
                'message'       => '2 hétre van szükségem'
            ]
        ];

        $partner = new Model_Project_Partner();
        $partner->apply($data);
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
