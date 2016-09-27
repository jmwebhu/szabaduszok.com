<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $data = [
            'industries' => [1, 2],
            'complex' => true
        ];

        $search = Project_Search_Factory::getAndSetSearch($data);
        $res = $search->search();
        echo Debug::vars($res);
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
