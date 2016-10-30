<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $pp = new Model_Project_Partner();
        echo Debug::vars($pp->find_all());
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
