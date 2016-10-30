<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $partner = new Model_Project_Partner(11);
        $partner->undoApplication();
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
