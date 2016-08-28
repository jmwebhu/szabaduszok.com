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
		$result = [];
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
			$matches = DB::select()->from('skills')->where('name', '=', $skill['name'])->and_where('skill_id', '!=', $skill['skill_id'])->execute()->as_array();						
			foreach ($matches as $match)
			{
				$alreadyInMatches = false;
				foreach ($result as $skillId => $item)
				{
					if (in_array($skill['skill_id'], $item['matches']))
					{
						$alreadyInMatches = true;
						break;
					}
				}
				
				if (!$alreadyInMatches)
				{
					if (!isset($result[$skill['skill_id']]))
					{
						$result[$skill['skill_id']] = ['matches' => []];
					}

					$result[$skill['skill_id']]['matches'][] = $match['skill_id'];
				}								
			}	
		}		
		
		foreach ($result as $skillId => $item)
		{
			$matches = $item['matches'];
			$userModel = new Model_User();
			$users = $userModel->find_all();

			foreach ($users as $user)
			{
				$userMatches = [];
				
				if (is_array($matches))
				{
					$userMatches = DB::select()->from('users_skills')->where('user_id', '=', $user->user_id)->and_where('skill_id', 'IN', $matches)->execute()->as_array();
				}
				
				$exists = count($userMatches) != 0;
				
				// vagy az eredeti skillel, vagy valamelyik egyezessel rendelkezik auser
				if ($exists || $user->has('skills', $skillId))
				{
					// ha nincs eredeti, hozzaadja
					if (!$user->has('skills', $skillId))
					{
						$user->add('skills', $skillId);
					}
					
					// Torli az egyezeseket
					if (is_array($matches))
					{
						DB::delete('users_skills')->where('user_id', '=', $user->user_id)->and_where('skill_id', 'IN', $matches)->execute();
					}									
				}									
			}
		}	
		
		foreach ($result as $skillId => $item)
		{
			$matches = $item['matches'];
			if (is_array($matches))
			{
				DB::delete('skills')->where('skill_id', 'IN', $matches)->execute();						
			}			
		}	
	}	
}