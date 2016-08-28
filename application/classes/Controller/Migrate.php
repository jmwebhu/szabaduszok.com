<?php

class Controller_Migrate extends Controller
{
	public function action_users()
	{
		Migrate::users();
	}
	
	public function action_signups()
	{
		Migrate::signups();
	}
	
	public function action_setnames()
	{
		Migrate::setNames();
	}
	
	public function action_searchtext()
	{
		Migrate::searchText();
	}
	
	public function action_userpassword()
	{
		Migrate::userpassword();
	}
	
	public function action_slug()
	{
		Migrate::slug();
	}
	
	public function action_mergetags()
	{
		Migrate::mergeTags();
	}
	
	public function before()
	{
		//$user = Auth::instance()->get_user();
		//if (!$user->is_admin)
		//{
		//	exit;
		//}
	}
}