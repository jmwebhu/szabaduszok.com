<?php

class Viewhelper_Project
{
	public static function getPageTitle($action = 'create')
	{
		switch ($action) {
			case 'create': return 'Új Szabadúszó projekt'; break;
			case 'edit': return 'Szabadúszó projekt szerkesztése: '; break;
		}
	}
	
	public static function hasIdInput($action = 'create')
	{
		switch ($action) {
			case 'create': return false; break;
			case 'edit': return true; break;
		}
	}
	
	public static function getFormAction($action = 'create', $project = null)
	{
		switch ($action) {
			case 'create': return Route::url('projectCreate'); break;
			case 'edit': return Route::url('projectUpdate', ['slug' => $project->getSlug()]); break;
		}
	}
	
	public static function getEmail($user = null, $action = 'create', $project = null)
	{
		switch ($action) {
			case 'create': return $user->email; break;
			case 'edit': return ($project->getEmail()) ? $project->getEmail() : $user->getEmail(); break;
		}
	}
	
	public static function getPhonenumber($user = null, $action = 'create', $project = null)
	{
		switch ($action) {
			case 'create': return $user->phonenumber; break;
			case 'edit': return ($project->getPhonenumber()) ? $project->getPhonenumber() : $user->getPhonenumber(); break;
		}
	}
	
	public static function getSalary(Entity_Project $project)
	{
		$salary = 0;		
		
		if ($project->getSalaryLow() == $project->getSalaryHigh() || !$project->getSalaryHigh()) {
			$salary = number_format($project->getSalaryLow(), 0, '.', ' ');
		} else {
			$salary = number_format($project->getSalaryLow(), 0, '.', ' ') . ' - ' . number_format($project->getSalaryHigh(), 0, '.', ' ');
		}		
		
		$postfix = ' Ft';
		if ($project->getSalaryType() == 1) {
			$postfix = ' Ft /óra';
		}
		
		return [
			'salary'	=> $salary,
			'postfix'	=> $postfix
		];
	}
	
	public static function getEditUrl(Entity_Project $project){
		return Route::url('projectUpdate', ['slug' => $project->getSlug()]);
	}
}