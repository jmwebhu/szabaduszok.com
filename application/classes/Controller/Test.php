<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {

        $model = new Model_Project();
        $projects = $model->getOrdered(10, 0);

        echo Debug::vars($projects);
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
