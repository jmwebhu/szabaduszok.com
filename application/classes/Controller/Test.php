<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $authorization = new Authorization_Project();
        $authorization->canCreate();
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
