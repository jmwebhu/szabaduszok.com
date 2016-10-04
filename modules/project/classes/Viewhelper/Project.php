<?php

class Viewhelper_Project
{
	public static function getPageTitle($action = 'create')
	{
		switch ($action) {
			case 'create': return 'Új Szabadúszó projekt'; break;
			case 'edit': return 'Szabadúszó projekt szerkesztése: '; break;
		}

        return 'Új Szabadúszó projekt';
	}
	
	public static function hasIdInput($action = 'create')
	{
		switch ($action) {
			case 'create': return false; break;
			case 'edit': return true; break;
		}

		return false;
	}
	
	public static function getFormAction($action = 'create', $project = null)
	{
		switch ($action) {
			case 'create': return Route::url('projectCreate'); break;
			case 'edit': return Route::url('projectUpdate', ['slug' => $project->getSlug()]); break;
		}

        return Route::url('projectCreate');
	}
	
	public static function getEmail($user = null, $action = 'create', $project = null)
	{
		switch ($action) {
			case 'create': return $user->email; break;
			case 'edit': return ($project->getEmail()) ? $project->getEmail() : $user->getEmail(); break;
		}

        return $user->email;
	}
	
	public static function getPhonenumber($user = null, $action = 'create', $project = null)
	{
		switch ($action) {
			case 'create': return $user->phonenumber; break;
			case 'edit': return ($project->getPhonenumber()) ? $project->getPhonenumber() : $user->getPhonenumber(); break;
		}

        return $user->phonenumber;
	}
	
	public static function getSalary(Entity_Project $project)
	{
		if ($project->getSalaryLow() == $project->getSalaryHigh() || !$project->getSalaryHigh()) {
			$salary = number_format($project->getSalaryLow(), 0, '.', ' ');
		} else {
		    $sb = SB::create(number_format($project->getSalaryLow(), 0, '.', ' '));
			$sb->append(' - ')->append(number_format($project->getSalaryHigh(), 0, '.', ' '));

            $salary = $sb->get();
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