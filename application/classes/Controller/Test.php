<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $project = new Entity_Project(48);
        $project->inactivate();

        echo Debug::vars(Cache::instance()->get('projects'));
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
