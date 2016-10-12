<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User extends Model_Auth_User
{
    const TYPE_FREELANCER = 1;
    const TYPE_EMPLOYER = 2;

	public $_nameField = 'lastname';
	public $_nameFieldSecond = 'firstname';
	
	protected $_table_name = 'users';
	protected $_primary_key = 'user_id';		
	
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
        'project_notifications' => [
            'model'         => 'User_Project_Notification',
            'foreign_key'   => 'user_id',
        ],
    ];

    public function getByEmail($email, $id)
    {
        $userWithEmail = $this->byEmail($email);

        if ($id) {
            $this->_model->where('user_id', '=', $id)->find();
            $userWithEmail  = $userWithEmail->byNotId($id);
        }

        return $userWithEmail->limit(1)->find();
    }
    
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
     * Visszaadja a megbizo nevet. Ha van kereszt es vezeteknev, akkor azokat, ha nincs
     * akkor cegnevet.
     *
     * @return string   Nev
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
    
    public function freelancerName()
    {
        if ($this->firstname)
        {
            return $this->firstname;
        }
        
        if ($this->lastname)
        {
            return $this->lastname;
        }
        
        return 'Szabadúszó';
    }

    /**
     * Visszaadja a megbizo nevet. Ha van kereszt es vezeteknev, akkor azokat, ha nincs
     * akkor cegnevet.
     *
     * @return string   Nev
     */
    public function employerName()
    {
        if ($this->firstname && $this->lastname)
        {
            return $this->firstname . ' ' . $this->lastname;
        }
        else
        {
            return $this->company_name;
        }
    }
    
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
    
    /**
     * Visszaadja cache -bol a kapott tipusu felhasznalokat
     * 
     * @param int $type		Tipus
     * @return array		Felhasznalok
     */
    public function getByType($type = 1)
    {
    	$users = $this->getAll();
    	$result = [];
    	 
    	foreach ($users as $user)
    	{
    		if ($user->type == $type)
    		{
    			$result[] = $user;
    		}
    	}
    	 
    	return $result;
    }
    
    /**
     * Visszaadja, hogy hany felhasznalo van a kapott tipusbol
     * 
     * @param int $type		Tipus
     * @return int $count
     */
    public function getCountByType($type = 1)
    {    	
    	$users = $this->getAll();
    	return AB::select()->from($users)->where('type', '=', $type)->execute()->count();    	    	
    }    
    
    public function hasProjectNotification()
    {
    	$notifications = $this->project_notifications->find_all();    	
    	
    	return !empty($notifications);
    }
    
    public function getMyRating($user)
    {
    	return DB::select('rating_point')
    		->from('users_ratings')
    		->where('user_id', '=', $user->user_id)
    		->and_where('rater_user_id', '=', $this->user_id)
    		->limit(1)
    		->execute()->get('rating_point');
    }

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

        $this->cacheToCollection();
        $this->updateSession();

        return $this;
    }

    /**
     * Szabaduszo regisztracio
     *
     * @param array $post					_POST adatok
     * @throws Exception_UserRegistration	Hiba eseten
     */
    public function registerFreelancer(array $post)
    {
    	// Szabaduszo
    	$post['type']               = 1;
        $post['landing_page_id']    = Model_Landing::byName(Arr::get($post, 'landing_page_name'));
    	$id                         = Arr::get($post, 'user_id');
    	
    	if ($id)
    	{
    		$this->where('user_id', '=', $id)->find();
    		// ORM peldanyositas
    		$userModel = new Model_User();
    			
    		// Felhasznalo lekerdeze a kapott e-mail cimmel
    		$userWithEmail = $userModel->byEmail(Arr::get($post, 'email'))->byNotId($id)->limit(1)->find();
    			
    		if (!Arr::get($post, 'password'))
    		{
    			unset($post['password']);
    			unset($post['password_again']);
    		}
    	}
    	else
    	{
    		// ORM peldanyositas
    		$userModel = new Model_User();
    			
    		// Felhasznalo lekerdeze a kapott e-mail cimmel
    		$userWithEmail = $userModel->byEmail(Arr::get($post, 'email'))->limit(1)->find();
    	}
    		
    	// Letezik ilyen, tehat hibat kell dobni
    	if ($userWithEmail->loaded())
    	{
    		throw new Exception_UserRegistration('Ezzel az e-mail címmel már regisztráltak. Kérjük válassz másikat, vagy jelentkezz be.');
    	}    			
		
		$this->fixPostalCode($post);
		$this->fixUrl($post, 'webpage');
		
    	if ($id)
    	{
    		// Szerkesztes
    		$submit = $this->update_user($post);
    	}
    	else
    	{
    		// Mentes
    		$submit = $this->create_user($post);
    	}
    
    	// Slug generalas
    	$this->saveSlug();
    
    	// Kapcsolatok hozzaadasa
        $this->addRelations($post, 1);
    	
    	// Projekt ertesito alapbeallitasa
    	$this->saveProjectNotificationByUser();
    
    	// Feltoltott fajlok mentese
    	$this->uploadFiles();      
    	
    	// Kereso szoveg
    	$this->saveSearchText();
    	
    	$this->last_login = time();
    	$this->save();
    		
    	// Hozzaadas cache gyujtemeneyhez
    	$this->cacheToCollection();
        
        // User frissitese Session -ben
        $this->updateSession();   
        
        // Torli a feliratkozast, ha letezik
        $signupModel = new Model_Signup();
        $signupModel->deleteIfExists($this->email);
    
    	return $this;
    }

    /**
     * Megbizo regisztracio
     *
     * @param array $post					_POST adatok
     * @throws Exception_UserRegistration	Hiba eseten
     */
    public function registerProjectowner(array $post)
    {
        // Megbizo
        $post['type']               = 2;
        $post['landing_page_id']    = $this->getLandingPageIdByPost($post);
        $id                         = Arr::get($post, 'user_id');

        if ($id)
        {
            $this->where('user_id', '=', $id)->find();

            // ORM peldanyositas
            $userModel = new Model_User();

            // Felhasznalo lekerdeze a kapott e-mail cimmel
            $userWithEmail = $userModel->byEmail(Arr::get($post, 'email'))->byNotId($id)->limit(1)->find();

            if (!Arr::get($post, 'password'))
            {
                unset($post['password']);
                unset($post['password_again']);
            }
        }
        else
        {
            // ORM peldanyositas
            $userModel = new Model_User();

            // Felhasznalo lekerdeze a kapott e-mail cimmel
            $userWithEmail = $userModel->byEmail(Arr::get($post, 'email'))->find();
        }

        // Letezik ilyen, tehat hibat kell dobni
        if ($userWithEmail->loaded())
        {
            throw new Exception_UserRegistration('Ezzel az e-mail címmel már regisztráltak. Kérjük válassz másikat, vagy jelentkezz be.');
        }

        $this->alterCheckboxValue($post);

        if ($id)
        {
            // Szerkesztes
            $submit = $this->update_user($post);
        }
        else
        {
            // Mentes
            $submit = $this->create_user($post);
        }

        // Slug generalas
        $this->saveSlug();

        // Kapcsolatok hozzaadasa
        $this->addRelations($post, 2);

        // Feltoltott fajlok mentese
        $this->uploadFiles();

        $this->last_login = time();
        $this->save();

        // Hozzaadas cache gyujtemeneyhez
        $this->cacheToCollection();

        // User frissitese Session -ben
        $this->updateSession();

        // Torli a feliratkozast, ha letezik
        $signupModel = new Model_Signup();
        $signupModel->deleteIfExists($this->email);

        return $this;
    }
	
	/**
	 * A weboldal ele teszi a 'http' stringet, ha nincs ott
	 * 
	 * @param array $post	_POST adatok
	 * @return string		Javitott URL
	 */
	protected function fixUrl(array &$post, $index)
	{
		$webpage = Arr::get($post, $index);
		if (!empty($webpage))
		{
			$needPrefix = (stripos($webpage, 'http://') === false && stripos($webpage, 'https://') === false);
		
			if ($needPrefix)
			{
				$post[$index] = 'http://' . $post[$index];				
			}
		}				
		
		return $post[$index];
	}
	
	/**
	 * NULL -ra allitja az iranyitoszamot, ha ures string
	 * 
	 * @param array $post	_POST adatok
	 * @return void
	 */
	protected function fixPostalCode(array &$post)
	{
		if (empty(Arr::get($post, 'address_postal_code')))
		{
			$post['address_postal_code'] = null;
		}
	}
    
    public function saveSearchText()
    {    	    	
    	$this->search_text = $this->collectSearchText();
    	$this->save();
    	
    	return $this;
    }

    public function collectSearchText()
    {
        $searchText = $this->name() . ' ' . $this->short_description . ' ' . date('Y-m-d') . ' ' . $this->address_city . ' ';
        
        if ($this->is_company)
        {
            $searchText .= $this->company_name . ' ';
        }    
        
        $relations = ($this->type == 1) ? ['industries', 'professions', 'skills'] : ['industries', 'professions'];
        
        foreach ($relations as $relation)
        {
            $text = $this->getRelationString($relation);
            $searchText .= $text . ' ';
        }

        return $searchText;
    }    
    
    /**
     * Hozzaadja a felhasznalot a megfelelo e-mail listahoz
     *
     * @param Api_Mailservice $api  Mailservice objektum
     * @param int $type             Tipus (1, 2)
     * @param int $id               Azonosito. Nem kotelezo
     * 
     * @return void  
     */
    public function addToMailService(Api_Mailservice $api, $type, $id = null)
    {
        /**
         * @var $api Api_Mailservice
         */
        $action     = ($id) ? 'update' : 'subscribe';
        $typeString = ($type == 1) ? 'freelancer' : 'projectowner';        
        $method     = $action . ucfirst($typeString);        
        
        $api->{$method}($this);
        
        return $method;
    }        
    
    /**
     * Visszaadja a landing oldal azonositojat a postban levo nev alapjan
     * 
     * @param array $post       _POST adatok
     * @return null|integer     ID vagy null
     */
    public function getLandingPageIdByPost(array $post)
    {
        $landing        = new Model_Landing();
        $landingPage    = $landing->where('name', '=', Arr::get($post, 'landing_page_name'))->limit(1)->find();
        
        return ($landingPage->loaded()) ? $landingPage->landing_page_id : null;
    }
    
    protected function updateSession()
    {
        Session::instance()->set('auth_user', $this);
    }
    
    /**
     * Belepes
     *
     * @param array $post	_POST adatok
     * @return string $url	Ide kell atiranyitani a usert
     *
     * @throws Exception_UserLogin
     */
    public function login(array $post)
    {
    	// Felhasznalo beleptetese
    	$isLoggedIn = Auth::instance()->login(Input::post('email'), Input::post('password'));
    
    	// Sikertelen belepes
    	if (!$isLoggedIn)
    	{
    		// Kivetelt dob
    		throw new Exception_UserLogin('Hibás e-mail vagy jelszó. Kérjük próbáld meg újra!');
    	}
    
    	// At kell -e iranyitani valami, vagy mehet default
    	$url = Session::instance()->get('redirectUrl');
    	$user = Auth::instance()->get_user();
    
    	// Nem volt session -ben semmilyen url
    	if (!$url)
    	{
    		// Profilra iranyitja    		
    		$url = ($user->type == 1) ? Route::url('freelancerProfile', ['slug' => $user->slug]) : Route::url('projectOwnerProfile', ['slug' => $user->slug]);
    	}        	
    	
    	// Cache modositasa
    	$userModel				= new Model_User();
    	$all					= $userModel->getAll();
    	$all[$user->user_id]	= $user;   
    	
    	Cache::instance()->set('users', $all);
    
    	return $url;
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
	 * Hozzaadja a felhasznalohoz a kulso profilokat
	 * 
	 * @param array			$post			_POST adatok
	 * @param Model_Profile	$profileModel	Egy ures Model_Profile objektum
	 * 
	 * @return void
	 */
	protected function addProfiles(array $post, Model_Profile $profileModel)
	{
		$profiles		= $profileModel->where('is_active', '=', 1)->find_all();
		$baseUrls		= [];
		
		foreach ($profiles as $profile)
		{
			/**
			 * @var $profile Model_Profile
			 */
			$baseUrls[$profile->pk()] = $profile->base_url;
		}
		
		// Vegmegy a post profilokon
		foreach (Arr::get($post, 'profiles') as $url)
		{
			$temp = ['url' => $url];
			$fixedUrl = $this->fixUrl($temp, 'url');
			
			// Vegmegy a rendszerben levo profilokon
			foreach ($baseUrls as $profileId => $baseUrl)
			{
				// Ha kapott url megfelel valamelyik rendszerben levonek
				if (stripos($fixedUrl, $baseUrl) !== false)
				{
					$userProfile				= new Model_User_Profile();
					$userProfile->profile_id	= $profileId;
					$userProfile->url			= $fixedUrl;
					$userProfile->user_id		= $this->pk();
					
					$userProfile->save();
				}
			}
		}
	}
    
    /**
     * Visszaadja a kapott kapcsolatokbol alkotott stringet.
     * A felhasznalohoz tartozo osszes entitas (iparagak, stb) nevet osszefuzi egy stringbe
     *
     * @param string $name		Kapcsolat neve
     * @return string			Eredmeny
     */
    public function getRelationString($name)
    {
    	$items = $this->{$name}->find_all();
    	$result = '';
    
    	foreach ($items as $i => $item)
    	{
    		$result .= ($i == count($items) - 1) ? $item->name : ($item->name . ', ');
    	}
    
    	return $result;
    }
    
    private function alterCheckboxValue(array &$post)
    {
    	$isCompany = Arr::get($post, 'is_company');
    
    	if ($isCompany == 'on')
    	{
    		$post['is_company'] = 1;
    	}
    
    	if (!$isCompany || $isCompany == 'off')
    	{
    		$post['is_company'] = 0;
    		$post['company_name'] = null;
    	}
    }
    
    /**
     * Jelszo emlekezteto. General egy uj jelszot, es elkuldi a kapott e-mail cimre
     *
     * @param string $email		E-mail cim
     * @return array $result
     */
    public function passwordReminder($email)
    {
    	// Lekerdezi a felhasznalot a kapott e-mail cimmel
    	$user = new Model_User();
    	$user = $user->getByColumn('email', $email);
    
    	// Van felhasznalo ezzel az e-mail cimmel
    	if ($user && $user->loaded())
    	{
    		$password = self::generatePassword();
    
    		$user->password = $password;
    		$user->save();
    
    		$html = Twig::getHtmlFromTemplate('Templates/newPasswordEmail.twig', ['email' => $email, 'password' => $password, 'user' => $user->firstname]);
    
    		Email::send($email, __('newPasswordSubject'), $html, __('newPasswordEmailType'));
    	}
    	else // Nincs ilyen felhasznalo
    	{
    		$result['error'] = true;
    		$result['message'] = 'noUserWithThisEmail';
    	}
    
    	return $result;
    }
    
    /**
     * General es visszaad egy random jelszot
     *
     * @param int $length   Jelszo hossza
     * @return string       Jelszo
     */
    public static function generatePassword($length = 6)
    {
    	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
    
    /**
     * Felhasznalo ertekeles
     * @param int $rating
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
     * Letrehozza a projekt ertesitoket
     *      
     * @param ORM $project    Uj projekt, amirol az ertesitest kell kuldeni
     * @param mixed $users              Azok a felhasznalok, akiknek ertesitest kell kuldeni az uj projektrol. Nme kotelezo. Ha nincs megadva lekerdezi
     */
    public function addToProjectNotification(ORM $project, $users = null)
    {
        // Nincsenek felhasznalok
        if (!$users || !is_array($users))
        {
            $usersAll       = $this->getAll();
            $freelancers    = AB::select()->from($usersAll)->where('type', '=', 1)->execute()->as_array();        
            $users          = $this->getUsersByProjectNotification($project, $freelancers);
        }       
        
        foreach ($users as $user)
        {
            /**
             * @var $user Model_User
             */

            $notification           = new Model_Project_Notification();
            $notification->user     = $user;
            $notification->project  = $project;
            $notification->save();      
        }       
    }
    
    /**
     * Visszaadja azokat a felhasznalokat, akiknek ertesítest kell kuldeni a kapoot projektrol
     *
     * @param ORM $project
     * @param array         $users
     */
    public function getUsersByProjectNotification(ORM $project, array $users)
    {    	
        $projectSkillIds    = $project->getRelationIds('skills');        
        $usersToNotify      = [];
            
        foreach ($users as $user)
        {            
            /**
             * @var $user Model_user
             */

            // Ker projekt ertesitot
            if ($user->need_project_notification && $user->type == 1)
            {
                $notification = new Model_User_Project_Notification();                
                switch ($user->skill_relation)
                {
                    // VAGY
                    case 1: default:
                        $has = $user->hasUserNotificationByOr($projectSkillIds, $notification);
                        break;
        
                        // ES
                    case 2:
                        $has = $user->hasUserNotificationByAnd($projectSkillIds, $notification);
                        break;
                }
        
                // Ha a projekt megfelel a felhasznalonak, bele kell tenni cache notification_queue -ba
                if ($has)
                {
                    $usersToNotify[] = $user;
                }
            }               
        }

        return $usersToNotify;
    }
    
    /**
     * Visszaadja, hogy kell -e a felhasznalonak ertesitot kuldeni a kapott projekt kepessegek alapjan VAGY kapcsolat eseten
     * Ha van legalabb egy kozos elem, vagy meg nem allitott be projekt ertesitot, akkor true -t ad vissza
     * 
     * @param array $projectSkillIds                Projekthez tartozo kepesseg azonositok
     * @param Model_User_Project_Notification       Projekt ertesito
     *
     * @return bool 			
     */
    protected function hasUserNotificationByOr(array $projectSkillIds, Model_User_Project_Notification $notification)
    {    	
    	// Felhasznalo projekt ertesito beallitasai
    	$userSkillIds = $notification->getSkillIdsByUser($this->user_id);    	
    	
    	// Ha meg nem allitotta be a projekt ertesitot, akkor mindenrol fog ertesites kapni
    	if (empty($userSkillIds))
    	{
    		return true;
    	}    	

	    $has = false;
    	
	    // Vegmegy a projekt osszes kepessegen
    	foreach ($userSkillIds as $skillId)
    	{
    		// Ha az adott projekt kepesseg megtalalhato a felhasznalo kepessegei kozt
    		if (in_array($skillId, $projectSkillIds))
    		{
    			$has = true;
    			break;
    		}
    	}
    	
    	return $has;
    }
    
    /**
     * Visszaadja, hogy kell -e a felhasznalonak ertesitot kuldeni a kapott projekt kepessegek alapjan ES kapcsolat eseten
     * Ha minden elem kozos, vagy meg nem allitott be projekt ertesitot, akkor true -t ad vissza
     *
     * @param array $projectSkillIds	Projekthez tartozo kepesseg azonositok
     * @param Model_User_Project_Notification       Projekt ertesito

     * @return bool
     */
    protected function hasUserNotificationByAnd(array $projectSkillIds, Model_User_Project_Notification $notification)
    {    	 
    	// Felhasznalo projekt ertesito beallitasai
    	$userSkillIds = $notification->getSkillIdsByUser($this->user_id);
    	 
    	// Ha meg nem allitotta be a projekt ertesitot, akkor mindenrol fog ertesites kapni
    	if (empty($userSkillIds))
    	{
    		return true;
    	}
    	 
    	$has = true;
    	
    	// Vegmegy a projekt osszes kepessegen
    	foreach ($userSkillIds as $skillId)
    	{
    		// Ha az adott projekt kepesseg nem talalhato a felhasznalo kepessegei kozt
    		if (!in_array($skillId, $projectSkillIds))
    		{
    			$has = false;
    			break;
    		}
    	}
    	
    	return $has;
    }
    
    /**
     * Kikuldi a cache -ben talalhato felhasznaloknak a projekt ertesitot
     *  
     */
    public function sendProjectNotification()
    {
        $notificationModel	= new Model_Project_Notification();
        $notifications		= $notificationModel->where('is_sended', '=', 0)->find_all();
		
		$signupModel		= new Model_Signup();
        $signups			= $signupModel->find_all();
		
		$limit = Kohana::$config->load('cron')->get('notificationLimit');                
        
        if ($notifications)
        {            
            foreach ($notifications as $i => $notification)
            {
                /**
                 * @var $notification    Model_Project_Notification
                 */                                             
                     
                $html = Twig::getHtmlFromTemplate('Templates/newProjectTemplate.twig', [
                    'user'      => $notification->user,
                    'project'   => $notification->project,
                    'root'      => URL::base(true, false)
                ]);
                     
                Email::send($notification->user->email, '[ÚJ PROJEKT]', $html);

                // Allapot frissitese
                $notification->is_sended = 1;
                $notification->save();
                
                if (($i + 1) == $limit)
                {
                    return false;
                }
            }
        }  
		
		/**
		 * @todo 
		 *  - osszegyujteni projekteket az elozo ciklusbol
		 *  - vegmenni feliratkozokon
		 *  - mindegyik projektrol kuldeni ertesitot
		 */
        
        return true;
    }
    
    /**
     * POST -ban kapoott szakterulet, kepesseg alapjan visszaadja a megfelelo objektumot.
     * Ha id -t kap, visszaad egy meglevot, ha string -et, akkor leterhozza, es visszaadja
     * 
     * Regisztracio, profil szerkesztes, projekt ertesito beallitasnal a select -ekben lehet meglevot valasztani, vagy ujat letrehozni. Uj eseten a $value string lesz (nev)
     * A POST feldolgozasanal ez a metodus adja vissza az objektumot.
     * 
     * A metodus hozza is adja a rekordot a user kapcsolataihoz, ha szukseges
     * 
     * $skill		= $this->getOrCreateRelation('php', 'skill')		Letrehoz egy uj kepesseget php nevvel
     * $profession	= $this->getOrCreateRelation(2, 'profession')		Visszaadja a letezo objektumot
     * 
     * @param string $value			Letezo rekord azonositoja, vagy uj rekord neve
     * @param string $relation		User kapcsolat neve EGYES SZAMBAN. skill, profession
     * @param bool   $addRelation	true eseten hozzaadja a letrehozott, vagy lekerdezett rekordot a user kapcsolataihoz
     * 
     * @return ORM $model			A letrehozott, vagy lekerdezett objektum
     */
    protected function getOrCreateRelation($value, $relation, $addRelation = true)
    {
    	$relation = strtolower($relation);
    	$class = 'Model_' . ucfirst($relation);
    	
    	// Azonosito, tehat letezik
    	if (Text::isId($value))
    	{    		
    		$intval = intval($value);
    		$model = new $class($intval);
    	}
    	else	// Uj entitas
    	{
    		// Letrehoz egy ujat
    		$model = new $class();
    		$model->name = $value;
    		$model->save();
    	
    		// Slug generalas, hozzadas cache gyujtemenyhez
    		$model->saveSlug();
    		$model->cacheToCollection();
    	}
    	
    	// Hozza kell adni a user kapcsolataihoz
    	if ($addRelation)
    	{
    		$plural =  $relation . 's';
    		$this->add($plural, $model);
    	}    	    	
    	
    	return $model;
    }
    
    /**
     * Visszaadja a felhasznalo osszes aktiv projektjet
     * 
     * @return mixed[]|unknown[][]
     */
    public function getProjects()
    {
    	$project 	= new Model_Project();
    	$projects	= $project->getAll();

    	$result		= AB::select()->from($projects)->where('is_active', '=', 1)->and_where('user_id', '=', $this->user_id)->order_by('created_at', 'DESC')->execute()->as_array();
    	
    	return $result;    
    }
    
    /**
     * Visszaadja a Szabaduszokat datum szerint novekvo sorrendben
     * Cache -bol dolgozik
     *
     * @return array 	Szabaduszok ORM
     */
    public function getOrdered($limit, $offset)
    {
    	// Csak az aktiv projektek
    	$users = $this->getAll();
    	 
    	return AB::select()->from($users)->where('type', '=', 1)->order_by('rating_points_avg', 'DESC')->limit($limit)->offset($offset)->execute()->as_array();
    }
    
    /**
     * Ket modelt hasonlit ossze, aszerint, hogy mikor hoztak letre
     *
     * @param ORM $a  Egyik projekt
     * @param ORM $b  Masik projekt
     *
     * @return integer          1, 0, -1
     */
    public function sortByrating(Model_User $a, Model_User $b)
    {
    	// Datum szerint csokkeno
    	if ($a->rating_points_avg < $b->rating_points_avg)
    	{
    		return 1;
    	}
    	elseif ($a->rating_points_avg > $b->rating_points_avg)
    	{
    		return -1;
    	}
    
    	return 0;
    }
    
    /**
     * Felhasznalo kereses. POST -tol fuggoen reszletes, vagy egyszeru
     *
     * @param array $post	_POST adatok
     * @param int   $type	Felhasznalo tipusa
     * @return array
     */
    public function search(array $post, $type = 1)
    {
    	// Reszletes kereses
    	if (Arr::get($post, 'complex'))
    	{
    		$users = $this->searchComplex($post, $type);
    	}
    	else 	// Egyszeru kereses
    	{
    		$users = $this->searchSimple($post, $type);
    	}
    	
    	usort($users, ['Model_User', 'sortByRating']);
    	 
    	return $users;
    }
    
    /**
     * Egyszeru felhasznalo kereso. Kifejezes alapjan
     *
     * @param array $post	_POST adatok
     * @return array 		Felhasznalok
     */
    protected function searchSimple(array $post, $type)
    {
    	$userModel		= new Model_User();        
    	$users			= $userModel->getAll();
        $users          = AB::select()->from($users)->where('type', '=', $type)->execute()->as_array();
    	$searchTerm 	= Arr::get($post, 'search_term');
    	 
    	if (!$searchTerm)
    	{
    		return $users;
    	}

        return AB::select()->from($users)->where('search_text', 'LIKE', $searchTerm)->execute()->as_array();    	       	 
    }
    
    /**
     * Reszletes felhasznalo kereso
     *  - Iparagak
     *  - Szakteruletek
     *  - Kepessegek
     *  - Kepesseg kapcsolat
     *
     * @param array $post	_POST adatok
     * @param int	$type	Felhasznalo tipusa
     * 
     * @return array		Projektek
     */
    protected function searchComplex(array $post, $type)
    {
    	$user 	= new Model_User();
        $users  = $user->getAll();
        $users  = AB::select()->from($users)->where('type', '=', $type)->execute()->as_array();
    
    	$users = $this->searchByIndustries($users, Arr::get($post, 'industries', []));
    	$users = $this->searchByProfessions($users, Arr::get($post, 'professions', []));
    	
    	// Kepesseg csak szabaduszo keresonel van
    	if ($type == 1)
    	{
    		$users = $this->searchBySkills($users, Arr::get($post, 'skills', []), Arr::get($post, 'skill_relation', 1));
    	}    	
    	
    	 
    	return $users;
    }
    
    /**
     * A kapott felhasznalokat szukiti a kapott iparagak alapjan.
     * Ha egy felhasznalo iparagai kozt LEGALABB egy megtalalhato a keresett iparagak kozul, akkor talaltnak tekinti.
     *
     * @param array $users				Felhasznalok (alapesetben az osszes rendszerben levo felhasznalo)
     * @param array $postIndustries		POST iparagak
     * 
     * @return array
     */
    protected function searchByIndustries(array $users, array $postIndustries)
    {
    	$result						= [];
    	 
    	if (empty($postIndustries))
    	{
    		return $users;
    	}
    	 
    	foreach ($users as $user)
    	{
    		/**
    		 * @var $user Model_User
    		 */    		
    			
    		// Vegmegy a postban kapott, keresett iparagakon. Ha barmelyik egyezik, beleteszi $result -ba a felhasznalot
    		foreach ($postIndustries as $postIndusrtyId)
    		{
    			// Ha a postbol kapott iparag megtalalhato a projekt iparagai kozott
    			if ($user->has('industries', $postIndusrtyId))
    			{
    				$result[] = $user;
    				break;
    			}
    		}
    	}
    
    	return $result;
    }
    
    /**
     * A kapott felhasznalokat szukiti a kapott szakteruletek alapjan.
     * Ha egy felhasznalo szakteruletei kozt LEGALABB egy megtalalhato a keresett szakteruletek kozul, akkor talaltnak tekinti.
     *
     * @param array $users				Felhasznalok (alapesetben az osszes rendszerben levo felhasznalo)
     * @param array $postProfessions	POST szakteruletek
     * 
     * @return array
     */
    protected function searchByProfessions(array $users, array $postProfessions)
    {
    	$result = [];
    	 
    	if (empty($postProfessions))
    	{
    		return $users;
    	}
    	 
    	foreach ($users as $user)
    	{
    		/**
    		 * @var $user Model_User
    		 */    		
    			
    		// Vegmegy a postban kapott, keresett iparagakon. Ha barmelyik egyezik, beleteszi $result -ba a felhasznalot
    		foreach ($postProfessions as $postProfessionId)
    		{
    			// Ha a postbol kapott iparag megtalalhato a projekt iparagai kozott
    			if ($user->has('professions', $postProfessionId))
    			{
    				$result[] = $user;
    				break;
    			}
    		}
    	}
    
    	return $result;
    }
    
    /**
     * A kapott felhasznalokat szukiti a kapott kepessegek alapjan.
     * skill_relation -tol fugg, hogy VAGY / ES kapcsolat van a keresett kepessegek kozott
     *
     * @param array $users				Felhasznalok (alapesetben a szakteruletekre szukitett projektek)
     * @param array $postSkills			POST kepessegek
     * @param int 	$skillRelation		Keresett kepessegek kapcsolata (ES / VAGY)
     *
     * @return array
     */
    protected function searchBySkills(array $users, array $postSkills, $skillRelation = 1)
    {
    	if (empty($postSkills))
    	{
    		return $users;
    	}
    	 
    	$result = [];
    	foreach ($users as $user)
    	{
    		/**
    		 * @var $user Model_User
    		 */    
    		switch ($skillRelation)
    		{
    			case 1: default:
    				$search = $this->searchBySkillsOr($user, $postSkills);
    				break;
    
    			case 2:
    				$search = $this->searchBySkillsAnd($user, $postSkills);
    				break;
    		}
    
    		if ($search)
    		{
    			$result[] = $user;
    		}
    	}
    
    	return $result;
    }
    
    /**
     * Visszaadja, hogy a kapott felhasznaloban megtalalhato -e, a kapott kepessegek BARMELYIKE (vagy kapcsolat)
     *
     * @param Model_User 	$user			Felhasznalo
     * @param array 		$postSkills		Keresett kepessegek
     *
     * @return bool
     */
    protected function searchBySkillsOr(Model_User $user, array $postSkills)
    {    	
    	$has		= false;
    	$userSkills	= $user->skills->find_all();
    	
    	if (empty($userSkills))
    	{
    		return false;
    	}
    
    	// Vegmegy a postban kapott, keresett kepessegeken. Ha barmelyik egyezik, true -e ad vissza
    	foreach ($postSkills as $postSkillId)
    	{
    		// Ha a postbol kapott kepsseg megtalalhato a felhasznalo kepessegei kozott
    		if ($user->has('skills', $postSkillId))
    		{
    			$has = true;
    			break;
    		}
    	}
    	 
    	return $has;
    }
    
    /**
     * Visszaadja, hogy a kapott felhasznaloban megtalalhato -e, a kapott kepessegek MINDEGYIKE (es kapcsolat)
     *
     * @param Model_User	$user			Felhasznalo
     * @param array 		$postSkills		Keresett kepessegek
     *
     * @return bool
     */
    protected function searchBySkillsAnd(Model_User $user, array $postSkills)
    {
    	$has		= true;
    	$userSkills	= $user->skills->find_all(); 
    	
    	if (empty($userSkills))
    	{
    		return true;
    	}
    
    	// Vegmegy a postban kapott, keresett kepessegeken. Ha barmelyik NEM egyezik, false -t ad vissza
    	foreach ($postSkills as $postSkillId)
    	{    	
    		// Ha a postbol kapott kepesseg NEM talalhato a felhasznalo kepessegei kozott
    		if (!$user->has('skills', $postSkillId))
    		{
    			$has = false;
    			break;
    		}
    	}
    
    	return $has;
    }
    
    /**
     * Kikuldi az aktualis, tipushoz tartozo lead magnetet a kapott e-mail cimre
     * 
     * @param string $email     Amire kuldeni kell a lead magnetet
     * @param integer $type     Amilyen tipusu lead magnetet kell kuldeni
     * 
     * @return void
     */
    public static function sendLeadMagnet($email, Model_Leadmagnet $leadMagnet, $type = 1)
    {
        $lead = $leadMagnet->getCurrentByType($type);
        
        if ($email && $lead->loaded())
        {
            $html = Twig::getHtmlFromTemplate('Templates/leadMagnet.twig', [
                'leadmagnet'    => $lead,
            ]);

            // Csak PROD, vagy STAG -ben kuld tenlyegesen e-mail
            if (Kohana::$environment == Kohana::PRODUCTION || Kohana::STAGING)
            {
                Email::send($email, '[ESETTANULMÁNY]', $html);
            }
            else    // Egyebkent csak egy file -t hoz letre
            {
                file_put_contents($email . '.html', $html);
            }               
        }                
    }
	
	/**
	 * Visszaadja a felhasznalohoz kapcsolat kulso pfoilok url -jet
	 * 
	 * @param Model_User_Profile $userProfile	Ures objektum
	 * @return array							Azonositok ['id' => 'url', ...]
	 */
	public function getProfileUrls(Model_User_Profile $userProfile)
	{
		$data			= [];
		$userProfiles	= $userProfile->where('user_id', '=', $this->pk())->find_all();
		
		foreach ($userProfiles as $profile)
		{
			$data[$profile->profile->pk()] = $profile->url;
		}
		
		return $data;
	}

    /**
     * @param int $type
     * @return Model_User
     */
	public static function createUser($type)
    {
        switch ($type) {
            case self::TYPE_FREELANCER:
                $user = new Model_User_Freelancer();
                break;

            case self::TYPE_EMPLOYER:
                $user = new Model_User_Employer();
                break;
        }

        return $user;
    }

    public function getType()
    {

    }
}
