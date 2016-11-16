<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Default auth user
 *
 * @package    Kohana/Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Model_Auth_User extends ORM {

	/**
	 * A user has many tokens and roles
	 *
	 * @var array Relationhips
	 */
	protected $_has_many = array(
		'user_tokens' => array('model' => 'User_Token'),
		'roles'       => array('model' => 'Role', 'through' => 'roles_users'),
	);

	/**
	 * Rules for the user model. Because the password is _always_ a hash
	 * when it's set,you need to run an additional not_empty rule in your controller
	 * to make sure you didn't hash an empty string. The password rules
	 * should be enforced outside the model or with a model helper method.
	 *
	 * @return array Rules
	 */
	public function rules()
	{
		return array(
			/*'password' => array(
				array('not_empty'),
			),
			'email' => array(
				array('not_empty'),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
			),*/
		);
	}

	/**
	 * Filters to run when data is set in this model. The password filter
	 * automatically hashes the password when it's set in the model.
	 *
	 * @return array Filters
	 */
	public function filters()
	{
		return array(
			'password' => array(
				array(array(Auth::instance(), 'hash'))
			)
		);
	}

	/**
	 * Labels for fields in this model
	 *
	 * @return array Labels
	 */
	public function labels()
	{
		return array(
			'username'         => 'username',
			'email'            => 'email address',
			'password'         => 'password',
		);
	}

	/**
	 * Complete the login for a user by incrementing the logins and saving login timestamp
	 *
	 * @return void
	 */
	public function complete_login()
	{
		if ($this->_loaded)
		{
			// Update the number of logins
			$this->logins = new Database_Expression('logins + 1');

			// Set the last login date
			$this->last_login = time();

			// Save the user
			$this->update();
		}
	}

	/**
	 * Tests if a unique key value exists in the database.
	 *
	 * @param   mixed    the value to test
	 * @param   string   field name
	 * @return  boolean
	 */
	public function unique_key_exists($value, $field = NULL)
	{
		if ($field === NULL)
		{
			// Automatically determine field by looking at the value
			$field = $this->unique_key($value);
		}

		return (bool) DB::select(array(DB::expr('COUNT(*)'), 'total_count'))
			->from($this->_table_name)
			->where($field, '=', $value)
			->where($this->_primary_key, '!=', $this->pk())
			->execute($this->_db)
			->get('total_count');
	}

	/**
	 * Allows a model use both email and username as unique identifiers for login
	 *
	 * @param   string  unique value
	 * @return  string  field name
	 */
	public function unique_key($value)
	{
		return Valid::email($value) ? 'email' : 'username';
	}

	/**
	 * Password validation for plain passwords.
	 *
	 * @param array $values
	 * @return Validation
	 */
	public static function get_password_validation($values)
	{
		return Validation::factory($values)
			->rule('password', 'min_length', array(':value', 6))
			->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
	}

	/**
	 * Create a new user
	 *
	 * Example usage:
	 * ~~~
	 * $user = ORM::factory('User')->create_user($_POST, array(
	 *	'username',
	 *	'password',
	 *	'email',
	 * );
	 * ~~~
	 *
	 * @param array $values
	 * @param array $expected
	 * @throws ORM_Validation_Exception
	 */
	public function create_user($values, $expected = null)
	{
		// Validation for passwords
		$extra_validation = Model_User::get_password_validation($values)
			->rule('password', 'not_empty');

		return $this->values($values, $expected)->create($extra_validation);
	}

	/**
	 * Update an existing user
	 *
	 * [!!] We make the assumption that if a user does not supply a password, that they do not wish to update their password.
	 *
	 * Example usage:
	 * ~~~
	 * $user = ORM::factory('User')
	 *	->where('username', '=', 'kiall')
	 *	->find()
	 *	->update_user($_POST, array(
	 *		'username',
	 *		'password',
	 *		'email',
	 *	);
	 * ~~~
	 *
	 * @param array $values
	 * @param array $expected
	 * @throws ORM_Validation_Exception
	 */
	public function update_user($values, $expected = NULL)
	{
		if (empty($values['password']))
		{
			unset($values['password'], $values['password_confirm']);
		}

		// Validation for passwords
		$extra_validation = Model_User::get_password_validation($values);

		return $this->values($values, $expected)->update($extra_validation);
	}

    public function updateSession()
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
        if (!$isLoggedIn) {
            // Kivetelt dob
            throw new Exception_UserLogin('Hibás e-mail vagy jelszó. Kérjük próbáld meg újra!');
        }

        // At kell -e iranyitani valami, vagy mehet default
        $url = Session::instance()->get('redirectUrl');
        $user = Auth::instance()->get_user();

        // Nem volt session -ben semmilyen url
        if (!$url) {
            // Profilra iranyitja
            $url = ($user->type == 1) ? Route::url('freelancerProfile', ['slug' => $user->slug]) : Route::url('projectOwnerProfile', ['slug' => $user->slug]);
        }

        $userModel				= new Model_User();
        $all					= $userModel->getAll();
        $all[$user->user_id]	= $user;

        Cache::instance()->set('users', $all);

        return $url;
    }

    public static function passwordReminder($email)
    {
        $user = AB::select()->from(new Model_User())->where('email', '=', $email)->limit(1)->execute()->current();

        if ($user && $user->loaded()) {
            $password = Text::generatePassword();

            $user->password = $password;
            $user->save();

            $html = Twig::getHtmlFromTemplate('Templates/newPasswordEmail.twig', ['email' => $email, 'password' => $password, 'user' => $user->firstname]);

            Email::send($email, __('newPasswordSubject'), $html);

            return true;
        }

        throw new Exception_UserNotFound('Sajnáljuk, nincs ilyen e-mail cím. Kérjük próbáld meg egy másikkal.');
    }

} // End Auth User Model
