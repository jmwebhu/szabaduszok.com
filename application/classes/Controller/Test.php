<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $model = new Model_Project();
        echo Debug::vars($model->object_name());
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
