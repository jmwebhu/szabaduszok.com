<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $signup = DB::select()->from('signups')->where('email', '=', 'heroldtamas1992@gmail.com')->execute()->current();
        echo Debug::vars($signup);

    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
