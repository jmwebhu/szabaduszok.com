<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $i = 1;
        $project = new Model_Project();

        echo Debug::vars(gettype($i), gettype($project));
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
