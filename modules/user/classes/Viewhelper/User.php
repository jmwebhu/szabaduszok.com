<?php

class Viewhelper_User
{
	public static function getEditUrl(Model_User $user)
	{
		return ($user->type == 1) ? Route::url('freelancerProfileEdit', ['slug' => $user->slug]) : Route::url('projectOwnerProfileEdit', ['slug' => $user->slug]); 
	}
	
	public static function getPageTitleFreelancer($action = 'registration')
	{
		switch ($action)
		{
			case 'registration': return 'Szabadúszó Regisztráció'; break;
			case 'edit': return 'Profil szerkesztése: '; break;
		}
	}
	
	public static function getPageTitleProjectowner($action = 'registration')
	{
		switch ($action)
		{
			case 'registration': return 'Megbízó Regisztráció'; break;
			case 'edit': return 'Profil szerkesztése: '; break;
		}
	}
	
	public static function hasPrivacyCheckbox($action = 'registration')
	{
		switch ($action)
		{
			case 'registration': return true; break;
			case 'edit': return false; break;
		}
	}
	
	public static function getPasswordText($action = 'registration')
	{
		switch ($action)
		{
			case 'registration': return 'Legalább 6 karakter'; break;
			case 'edit': return 'Legalább 6 karakter. Ha nem módosítod, hagyd üresen!'; break;
		}
	}
	
	public static function hasIdInput($action = 'registration')
	{
		switch ($action)
		{
			case 'registration': return false; break;
			case 'edit': return true; break;
		}	
	}
	
	public static function getFormActionFreelancer($action = 'registration', $user = null)
	{
		switch ($action)
		{
			case 'registration': return Route::url('freelancerRegistration') ; break;
			case 'edit': return Route::url('freelancerProfileEdit', ['slug' => $user->slug]); break;
		}
	}
	
	public static function getFormActionProjectowner($action = 'registration', $user = null)
	{
		switch ($action)
		{
			case 'registration': return Route::url('projectOwnerRegistration') ; break;
			case 'edit': return Route::url('projectOwnerProfileEdit', ['slug' => $user->slug]); break;
		}
	}
	
	public static function hasPasswordRules($action = 'registration')
	{
		switch ($action)
		{
			case 'registration': return true; break;
			case 'edit': return false; break;
		}
	}
	
	public static function hasPicture(Model_User $user)
	{
		if ($user->loaded() && $user->profile_picture_path)
		{
			return true;
		}
		
		return false;
	}
	
	public static function hasCv(Model_User $user)
	{
		if ($user->loaded() && $user->cv_path)
		{
			return true;
		}
	
		return false;
	}
	
	public static function getIndustriesForProfile(Model_User $user)
	{
		$industryModel = new Model_Industry();
		$industries = $industryModel->getAll();
		$result = [];
		
		if ($user->hasProjectNotification())
		{
			$notifications = $user->project_notifications->where('industry_id', 'IS NOT', null)->find_all();
			$ids = [];
			
			foreach ($notifications as $notification)
			{
				$ids[] = $notification->industry_id;
			}
			
			foreach ($industries as $industry)
			{
				$result[] = [
					'id'		=> $industry->industry_id,
					'name'		=> $industry->name,
					'selected'	=> (in_array($industry->industry_id, $ids)) ? 'selected' : '',
				];
			}
		}
		else 
		{
			foreach ($industries as $industry)
			{
				$result[] = [
					'id'		=> $industry->industry_id,
					'name'		=> $industry->name,
					'selected'	=> '',
				];
			}			
		}
		
		return $result;
	}
	
	public static function getProfessionsForProfile(Model_User $user)
	{
		$professionModel = new Model_Profession();
		$professions = $professionModel->getAll();
		$result = [];
	
		if ($user->hasProjectNotification())
		{
			$notifications = $user->project_notifications->where('profession_id', 'IS NOT', null)->find_all();
			$ids = [];
				
			foreach ($notifications as $notification)
			{
				$ids[] = $notification->profession_id;
			}
				
			foreach ($professions as $profession)
			{
				$result[] = [
					'id'		=> $profession->profession_id,
					'name'		=> $profession->name,
					'selected'	=> (in_array($profession->profession_id, $ids)) ? 'selected' : '',
				];
			}
		}
	
		return $result;
	}
	
	public static function getSkillsForProfile(Model_User $user)
	{
		$skillModel = new Model_Skill();
		$skills = $skillModel->getAll();
		$result = [];
	
		if ($user->hasProjectNotification())
		{
			$notifications = $user->project_notifications->where('skill_id', 'IS NOT', null)->find_all();
			$ids = [];
	
			foreach ($notifications as $notification)
			{
				$ids[] = $notification->skill_id;
			}
	
			foreach ($skills as $skill)
			{
				$result[] = [
					'id'		=> $skill->skill_id,
					'name'		=> $skill->name,
					'selected'	=> (in_array($skill->skill_id, $ids)) ? 'selected' : '',
				];
			}
		}
	
		return $result;
	}
}