<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $ind = new Model_Project_Industry();
        $model = $ind->getEndRelationModel();

        echo Debug::vars($model->primary_key());
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
