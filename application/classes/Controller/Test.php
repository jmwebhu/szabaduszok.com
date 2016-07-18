<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {	
        $user		= new Model_User(271);
		$profiles	= $user->getProfileIds();
		
		echo Debug::vars($profiles);
		
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
