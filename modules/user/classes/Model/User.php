<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User extends Model_Auth_User
{
	public $_nameField          = 'lastname';
	public $_nameFieldSecond    = 'firstname';
	
	protected $_table_name      = 'users';
	protected $_primary_key     = 'user_id';
	
	protected $_created_column = [
		'column' => 'created_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_updated_column = [
		'column' => 'updated_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_table_columns = [
		'user_id'					=> ['type' => 'int', 'key' => 'PRI'],
		'lastname'					=> ['type' => 'string', 'null' => true],
		'firstname'					=> ['type' => 'string', 'null' => true],
		'email'						=> ['type' => 'string', 'null' => true],
		'password'					=> ['type' => 'string', 'null' => true],
		'logins'					=> ['type' => 'int', 'null' => true],
		'last_login'				=> ['type' => 'int', 'null' => true],
		'address_postal_code'		=> ['type' => 'int', 'null' => true],
		'address_city'				=> ['type' => 'string', 'null' => true],
		'address_street'			=> ['type' => 'string', 'null' => true],
		'phonenumber'				=> ['type' => 'string', 'null' => true],
		'slug'						=> ['type' => 'string', 'null' => true],
		'type'						=> ['type' => 'int', 'null' => true],
		'min_net_hourly_wage'		=> ['type' => 'decimal', 'null' => true],
		'short_description'			=> ['type' => 'string', 'null' => true],
		'profile_picture_path'		=> ['type' => 'string', 'null' => true],
		'list_picture_path'			=> ['type' => 'string', 'null' => true],
		'cv_path'					=> ['type' => 'string', 'null' => true],
		'is_company'				=> ['type' => 'int', 'null' => true],
		'company_name'				=> ['type' => 'string', 'null' => true],
		'created_at'				=> ['type' => 'datetime', 'null' => true],
		'updated_at'				=> ['type' => 'datetime', 'null' => true],
		'rating_points_sum'			=> ['type' => 'int', 'null' => true],
		'raters_count'				=> ['type' => 'int', 'null' => true],
		'rating_points_avg'			=> ['type' => 'decimal', 'null' => true],
		'skill_relation'			=> ['type' => 'int', 'null' => true],
		'is_admin'					=> ['type' => 'int', 'null' => true],
		'search_text'				=> ['type' => 'string', 'null' => true],
		'old_user_id'				=> ['type' => 'int', 'null' => true],
		'password_plain'			=> ['type' => 'string', 'null' => true],
		'landing_page_id'			=> ['type' => 'int', 'null' => true],
		'need_project_notification'	=> ['type' => 'int', 'null' => true],
		'webpage'					=> ['type' => 'string', 'null' => true],
	];
	
    protected $_has_many = [
        'projects' => [
            'model'         => 'Project',
            'foreign_key'   => 'user_id'
        ],
    	'industries' => [
    		'model'     	=> 'Industry',
   			'through'		=> 'users_industries',
   			'far_key'		=> 'industry_id',
    		'foreign_key'	=> 'user_id',
   		],
    	'professions' => [
   			'model'     	=> 'Profession',
    		'through'		=> 'users_professions',
    		'far_key'		=> 'profession_id',
    		'foreign_key'	=> 'user_id',
   		],
    	'skills' => [
    		'model'     	=> 'Skill',
   			'through'		=> 'users_skills',
    		'far_key'		=> 'skill_id',
    		'foreign_key'	=> 'user_id',
   		],
    	'ratings' => [
    		'model'     	=> 'User',
    		'through'		=> 'users_ratings',
    		'far_key'		=> 'rater_user_id',
    		'foreign_key'	=> 'user_id',
    	],
		'profiles' => [
    		'model'     	=> 'User_Profile',
   			'far_key'		=> 'profile_id',
    		'foreign_key'	=> 'user_id',
   		],
    	/**
         * Felhasznalo profilon megjeleno projekt ertesito beallitasok.
         */    
        'project_notification_industries' => [
            'model'         => 'User_Project_Notification_Industry',
            'foreign_key'   => 'user_id',
        ],
        'project_notification_professions' => [
            'model'         => 'User_Project_Notification_Profession',
            'foreign_key'   => 'user_id',
        ],
        'project_notification_skills' => [
            'model'         => 'User_Project_Notification_Skill',
            'foreign_key'   => 'user_id',
        ],
    ];

    public function byType($type)
    {
        return $this->where('type', '=', $type);
    }

    public function byEmail($email)
    {
        return $this->where('email', '=', $email);
    }

    public function byNotId($id)
    {
        return $this->where('user_id', '!=', $id);
    }

    public function getByEmail($email, $id)
    {
        $model          = new Model_User();
        $userWithEmail  = $model->byEmail($email);

        if ($id) {
            $this->where('user_id', '=', $id)->find();
            $userWithEmail  = $userWithEmail->byNotId($id);
        }

        return $userWithEmail->limit(1)->find();
    }

    /**
     * @todo csere
     */
    public function last_login($format = null)
    {
    	$format = ($format) ? $format : 'Y-m-d';
    	
    	if (!$this->last_login)
    	{
    		return 'Még nem lépett be';
    	}
    	
    	$date = $this->last_login; 
    	
    	return date($format, $date);
    }

    /**
     * @todo csere
     */
    public function name()
    {
        $name = ($this->lastname && $this->firstname) ? $this->lastname . ' ' . $this->firstname : $this->company_name;
        
        if (!$name && $this->type == 1)
        {
            $name = 'Szabadúszó';
        }
        
        return $name;
    }

    /**
     * @todo csere. Csak Model_User_Freelancer, es Entity_User_Freelancer
     * @return bool
     */
    public function hasProjectNotification()
    {
    	$notifications = $this->project_notifications->find_all();
    	return !empty($notifications);
    }

    /**
     * @param array $data
     * @return Model_User
     */
    public function submit(array $data)
    {
        $id = Arr::get($data, 'user_id');

        if ($id) {
            $this->update_user($data);
        } else {
            $this->create_user($data);
        }

        $this->saveSlug();
        $this->addRelations($data);

        $this->last_login = time();
        $this->save();

        return $this;
    }

    /**
     * @param array $post
     */
    public function addRelations(array $post)
    {        
        $this->removeAll('users_industries', 'user_id');
    	$this->removeAll('users_professions', 'user_id');

        $this->addRelation($post, new Model_User_Industry(), new Model_Industry());
        $this->addRelation($post, new Model_User_Profession(), new Model_Profession());
    }

    /**
     * @param $rating
     * @return array
     */
    public function rate($rating)
    {
    	$this->rating_points_sum += intval($rating);
    	$this->raters_count++;
    	$this->rating_points_avg = $this->rating_points_sum / $this->raters_count;
    
    	$this->save();
    
    	$rater = Auth::instance()->get_user();
    
    	$this->add('ratings', $rater, ['col' => 'rating_point', 'value' => $rating]);
    	
    	$this->cacheToCollection();
    
    	$result = ['error' => false, 'rating' => number_format($rating, 0), 'avg' => number_format($this->rating_points_avg, 1), 'raters_count' => $this->raters_count];    	    
    
    	return $result;
    }

	/**
	 * @param Model_User_Profile $userProfile
	 * @return array
	 */
	public function getProfileUrls(Model_User_Profile $userProfile)
	{
		$data			= [];
		$userProfiles	= $userProfile->where('user_id', '=', $this->pk())->find_all();
		
		foreach ($userProfiles as $profile) {
			$data[$profile->profile->pk()] = $profile->url;
		}
		
		return $data;
	}
}
