<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {	        
		$skill = 67;
		$count = DB::select([DB::expr('COUNT(user_id)'), 'count'])->from('users_skills')->where('skill_id', '=', $skill)->execute()->get('count');
		echo Debug::vars($count);		
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
