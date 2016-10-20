<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $path = Kohana_Core::auto_load('Pager\X');
        echo Debug::vars($path);
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
