<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $data = [
            'user_id'       => 2020,
            'project_id'    => 10023
        ];

        $partner = new Model_Project_Partner(18);
        $partner->cancelParticipation();
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
