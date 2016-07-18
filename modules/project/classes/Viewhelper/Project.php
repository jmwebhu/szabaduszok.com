<?php

class Viewhelper_Project
{
	public static function getPageTitle($action = 'create')
	{
		switch ($action)
		{
			case 'create': return 'Új Szabadúszó projekt'; break;
			case 'edit': return 'Szabadúszó projekt szerkesztése: '; break;
		}
	}
	
	public static function hasIdInput($action = 'create')
	{
		switch ($action)
		{
			case 'create': return false; break;
			case 'edit': return true; break;
		}
	}
	
	public static function getFormAction($action = 'create', $project = null)
	{
		switch ($action)
		{
			case 'create': return Route::url('projectCreate'); break;
			case 'edit': return Route::url('projectUpdate', ['slug' => $project->slug]); break;
		}
	}
	
	public static function getEmail($user = null, $action = 'create', $project = null)
	{
		switch ($action)
		{
			case 'create': return $user->email; break;
			case 'edit': return ($project->email) ? $project->email : $user->email; break;
		}
	}
	
	public static function getPhonenumber($user = null, $action = 'create', $project = null)
	{
		switch ($action)
		{
			case 'create': return $user->phonenumber; break;
			case 'edit': return ($project->phonenumber) ? $project->phonenumber : $user->phonenumber; break;
		}
	}
	
	public static function getSalary(Model_Project $project)
	{
		$salary = 0;		
		
		if ($project->salary_low == $project->salary_high || !$project->salary_high)
		{
			$salary = number_format($project->salary_low, 0, '.', ' ');
		}
		else
		{
			$salary = number_format($project->salary_low, 0, '.', ' ') . ' - ' . number_format($project->salary_high, 0, '.', ' ');
		}		
		
		$postfix = ' Ft';
		if ($project->salary_type == 1)
		{
			$postfix = ' Ft /óra';
		}
		
		return [
			'salary'	=> $salary,
			'postfix'	=> $postfix
		];
	}
	
	public static function getEditUrl(Model_Project $project)
	{
		return Route::url('projectUpdate', ['slug' => $project->slug]);
	}
}