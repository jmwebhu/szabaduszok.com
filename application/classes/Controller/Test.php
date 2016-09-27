<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {	        
        $simpleSearch = Project_Search_Factory::getAndSetSearch(['complex' => false]);
        $res = $simpleSearch->search(['search_term' => 'web'], new Model_Project());
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
