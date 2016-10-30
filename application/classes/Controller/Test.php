<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $partner = DB::select()
            ->from('projects_partners')
            ->where('user_id', '=', 1)
            ->and_where('project_id', '=', 1)
            ->limit(1)
            ->execute()->current();

        echo Debug::vars($partner);
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
