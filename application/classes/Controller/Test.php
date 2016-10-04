<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        try {

            echo 'try';
            throw new Exception();
        } catch (Exception $ex) {
            echo 'catch';
        }

        echo 'return';
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
