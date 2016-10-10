<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $prpr = new Model_Project_Profession();
        $all = $prpr->getAll();

        echo Debug::vars($all);
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
