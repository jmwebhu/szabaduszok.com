<?php

/**
 * class Migrate
 * 
 * Migracio v1 -> v2
 * 
 * @author Joó Martin <joomartin@jmweb.hu>
 *
 */
class Migrate
{
	public static function users()
	{
		exit;
		// v1 adatbazis kapcsolat hasznalata
		$v1 = Database::instance('v1');
		
		$users = DB::select()
			->from('users')
			->execute($v1)->as_array();				
		
		foreach ($users as $user)			
		{		
			$values = [
				'email'					=> Arr::get($user, 'email'),
				'password'				=> Arr::get($user, 'password'),
				'address_postal_code'	=> Arr::get($user, 'address_postal_code'),
				'address_city'			=> Arr::get($user, 'address_city'),
				'address_street'		=> Arr::get($user, 'address_street'),
				'phonenumber'			=> Arr::get($user, 'phonenumber'),
				'slug'					=> Arr::get($user, 'slug'),
				'type'					=> 2,
				'is_company'			=> 0,
				'created_at'			=> Arr::get($user, 'created_at'),
				'old_user_id'			=> Arr::get($user, 'id'),
				'company_name'			=> Arr::get($user, 'name')
			];
			
			$insert = DB::insert('users', array_keys($values))->values($values)->execute();
		}		
		
		$userModel 	= new Model_User();
		$users 		= $userModel->where('type', '=', 2)->find_all();
		
		foreach ($users as $user)
		{
			/**
			 * $user Model_User
			 */
			$user->saveSearchText();
		}				
	}
	
	public static function signups()
	{
		exit;
		// v1 adatbazis kapcsolat hasznalata
		$v1 = Database::instance('v1');
		
		$signups = DB::select()
			->from('signups')
			->where('su_is_active', '=', 1)
			->execute($v1)->as_array();
						
		foreach ($signups as $signup)
		{			
			$userModel = new Model_User();
			$user = $userModel->where('email', '=', Arr::get($signup, 'su_email'))->limit(1)->find();
			
			if (!$user->loaded())
			{
				$password = Model_User::generatePassword();
				$values = [
					'email'					=> Arr::get($signup, 'su_email'),
					'password_plain'		=> $password,
					'password'				=> Auth::instance()->hash($password),
					'type'					=> 1,
					'created_at'			=> Arr::get($signup, 'su_created_at'),
					'old_user_id'			=> Arr::get($signup, 'su_id'),
				];
				
				$insert = DB::insert('users', array_keys($values))->values($values)->execute();
			}						
		}
		
		if (!$user->loaded())
		{
			$userModel 	= new Model_User();
			$users 		= $userModel->where('type', '=', 1)->find_all();
			
			foreach ($users as $user)
			{
				/**
				 * $user Model_User
				 */
				$user->slug = 'szabaduszo-' . $user->user_id;
				$user->save();
			}	
		}			
	}
	
	public static function setNames()
	{
		$migrates = DB::select()
			->from('users_migrate')
			->where('type', '=', 2)
			->execute()->as_array();		
		
		foreach ($migrates as $user)
		{
			$normalUser = DB::select()->from('users')->where('old_user_id', '=', $user['old_user_id'])->limit(1)->execute()->current();
			
			$values = [
				'lastname' 		=> $user['lastname'],
				'firstname' 	=> $user['firstname'],
				'company_name'	=> $user['company_name'],
				'is_company' 	=> $user['is_company']
			];
			
			$update = DB::update('users')->set($values)->where('user_id', '=', $normalUser['user_id'])->execute();
		}
	}
	
	public static function searchText()
	{
		$projectModel	= new Model_Project();
		$projects		= $projectModel->find_all();
		
		foreach ($projects as $project)
		{
			/**
			 * @var $project Model_Project
			 */
			$project->search_text = $project->getSearchText();
			$project->save();
		}
	}
	
	public static function userpassword()
	{
		$user	= new Model_User();
		$users  = $user->where('password', 'IS', null)->find_all();
		
		foreach ($users as $user)
		{
			/**
			 * @var $user Model_User
			 */
			
			$password 				= Model_User::generatePassword();			
			$user->password 		= Auth::instance()->hash($password);
			$user->password_plain	= $password;
			
			$user->save();
		}
	}
	
	public static function slug()
	{
		$user 	= new Model_User();
		$users 	= $user->find_all();	
		
		foreach ($users as $user)
		{
		 	/**
		 	 * @var $user Model_User
			 */
			
			if ($user->type == 1)
			{
				$user->slug = 'szabaduszo-' . $user->user_id;
				$user->save();
			}
			else 
			{
				$user->saveSlug();
			}
		}
	}
	
	public static function mergeTags()
	{
		/**
		 * @todo
		 * - Lekerdezni az osszes skill -t
		 * - Vegmenni, es keresni case insensitive egyezeseket
		 * - Ha vannak egyezesek, le kell kerdezni az osszes user_skills -t, ami ezt hasznalja
		 * - A user_skills -t atmenetileg NULL -ra kell allitani
		 * - Torolni az osszes egyezest
		 * - Az eredeti talalatot, konvertalni kisbetusre
		 * - Minden user_skills -t, ami jelenleg NULL, at kell irni, az eredeti talalatra
		 * 
		 * - !!!Jelolni az egyezesekben, hogy feldolgozva
		 */
		
		$skills = DB::select()->from('skills')->execute()->as_array();
		foreach ($skills as $i => $skill)
		{
			if (!count($skills))
			{
				echo Debug::vars('continue: ', $skill['skill_id']);
				continue;
			}
			
			$matches = DB::select()->from('skills')->where('name', '=', $skill['name'])->and_where('skill_id', '!=', $skill['skill_id'])->execute()->as_array();						
			foreach ($matches as $match)
			{
				$userSkills = DB::select()->from('users_skills')->where('skill_id', '=', $match['skill_id'])->execute()->as_array();
				foreach ($userSkills as $userSkill)
				{
					DB::update('users_skills')->set(['skill_id' => null])->where('skill_id', '=', $userSkill['skill_id'])->execute();					
				}
				
				DB::delete('skills')->where('skill_id', '=', $match['skill_id'])->execute();
				
				foreach ($skills as $j => $tmpSkill) 
				{
					if ($tmpSkill['skill_id'] == $match['skill_id'])
					{
						unset($skills[$j]);
					}
				}
			}
			
			$newSkillName = mb_strtolower($skill['name']);
			DB::update('skills')->set(['name' => $newSkillName])->where('skill_id', '=', $skill['skill_id'])->execute();			
			DB::update('users_skills')->set(['skill_id' => $skill['skill_id']])->where('skill_id', 'IS', null)->execute();
			
			unset($skills[$i]);									
		}
	}
}