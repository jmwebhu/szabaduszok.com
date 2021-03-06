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
    public static function updateUserProjectNotifications()
    {
        self::truncateNotificationTables();
        $notifications = DB::select()->from('users_project_notification')->execute()->as_array();

        foreach ($notifications as $notification) {
            $tableData = self::getTableDataFrom($notification);

            try {
                DB::insert($tableData['table'], ['user_id', $tableData['column'], 'created_at'])->values([
                    'user_id'               => $notification['user_id'],
                    $tableData['column']    => Arr::get($notification, $tableData['column']),
                    'created_at'            => $notification['created_at'],
                ])->execute();
            } catch (Database_Exception $ex) {
                Log::instance()->addException($ex);
                continue;
            } catch (Exception $ex) {
                echo Debug::vars($ex);
                exit;
            }
        }
    }

    protected static function truncateNotificationTables()
    {
        Database::instance()->query(Database::DELETE, 'TRUNCATE TABLE users_project_notification_industries');
        Database::instance()->query(Database::DELETE, 'TRUNCATE TABLE users_project_notification_professions');
        Database::instance()->query(Database::DELETE, 'TRUNCATE TABLE users_project_notification_skills');
    }

    /**
     * @param array $notification
     * @return array
     * @throws Exception
     */
    protected static function getTableDataFrom(array $notification)
    {
        if (Arr::get($notification, 'industry_id')) {
            $table  = 'users_project_notification_industries';
            $column = 'industry_id';
        } elseif (Arr::get($notification, 'profession_id')) {
            $table  = 'users_project_notification_professions';
            $column = 'profession_id';
        } elseif (Arr::get($notification, 'skill_id')) {
            $table  = 'users_project_notification_skills';
            $column = 'skill_id';
        } else {
            throw new Exception('Invalid notification type');
        }

        return [
            'table'     => $table,
            'column'    => $column
        ];
    }

	public static function mergeTags()
	{
	    return true;
		self::mergeTagsSkills();
		self::mergeTagsProfessions();
	}	
	
	protected static function mergeTagsProfessions()
	{
		echo Debug::vars('mergeprofs');
		$result = [];
		$skills = DB::select()->from('professions')->execute()->as_array();
		
		foreach ($skills as $i => $skill)
		{	
			$matches = DB::select()->from('professions')->where('name', '=', $skill['name'])->and_where('profession_id', '!=', $skill['profession_id'])->execute()->as_array();						
			foreach ($matches as $match)
			{
				$alreadyInMatches = false;
				foreach ($result as $skillId => $item)
				{
					if (in_array($skill['profession_id'], $item['matches']))
					{
						$alreadyInMatches = true;
						break;
					}
				}
				
				if (!$alreadyInMatches)
				{
					if (!isset($result[$skill['profession_id']]))
					{
						$result[$skill['profession_id']] = ['matches' => []];
					}

					$result[$skill['profession_id']]['matches'][] = $match['profession_id'];
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
					$userMatches = DB::select()->from('users_professions')->where('user_id', '=', $user->user_id)->and_where('profession_id', 'IN', $matches)->execute()->as_array();
				}
				
				$exists = count($userMatches) != 0;
				
				// vagy az eredeti skillel, vagy valamelyik egyezessel rendelkezik auser
				if ($exists || $user->has('professions', $skillId))
				{
					// ha nincs eredeti, hozzaadja
					if (!$user->has('professions', $skillId))
					{
						$user->add('professions', $skillId);
					}
					
					// Torli az egyezeseket
					if (is_array($matches))
					{
						DB::delete('users_professions')->where('user_id', '=', $user->user_id)->and_where('profession_id', 'IN', $matches)->execute();
					}									
				}									
			}
		}
		
		foreach ($result as $skillId => $item)
		{
			$matches = $item['matches'];
			if (is_array($matches))
			{
				DB::delete('professions')->where('profession_id', 'IN', $matches)->execute();						
			}			
		}
	}
	
	protected static function mergeTagsSkills()
	{
		$result = [];
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