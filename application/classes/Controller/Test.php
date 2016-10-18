<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        echo Debug::vars(Auth::instance()->get_user());
        exit;
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
