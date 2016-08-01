<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {	        
		$pass = Model_User::generatePassword();
		echo Debug::vars($pass);
		echo Debug::vars(Auth::instance()->hash($pass));
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
