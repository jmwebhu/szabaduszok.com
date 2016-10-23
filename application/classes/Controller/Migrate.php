<?php

class Controller_Migrate extends Controller
{
    public function action_update_user_project_notifications()
    {
        Migrate::updateUserProjectNotifications();
    }
	
	public function action_mergetags()
	{
		Migrate::mergeTags();
	}
	
	public function before()
	{
		$user = Auth::instance()->get_user();
		if (Kohana::$environment == Kohana::PRODUCTION && !$user->is_admin)
		{
			exit;
		}
	}
}