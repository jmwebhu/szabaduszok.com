<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $data = [
            'industries' => [1,2,3,4,5,6],
            'complex' => true
        ];

        $complexSearch = Project_Search_Factory::getAndSetSearch($data);
        $res = $complexSearch->search();
        echo Debug::vars($res);
        exit;
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
