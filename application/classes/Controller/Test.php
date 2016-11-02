<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $project = new Model_Project(10023);
        echo Debug::vars($project->partners->find_all());
        exit;
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
