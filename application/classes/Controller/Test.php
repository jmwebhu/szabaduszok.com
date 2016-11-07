<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $res = DB::select()
            ->from('projects_partners')
            ->where('user_id', '=', 2022)
            ->and_where('project_id', '=', 10023)
            ->and_where('type', '=', Model_Project_Partner::TYPE_CANDIDATE)
            ->limit(1)
            ->execute()->count();


        echo Debug::vars($res);
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
