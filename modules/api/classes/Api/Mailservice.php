<?php

/**
 * abstract class Api_Mailservice
 * E-mail cimlista kezelo API ososztaly. Ez az osztaly definialja az alap viselkedest. Minden konkret szolgaltatas API osztalya ebbol orokol es az
 *
 * Api_Mailservice_<service> nevet viseli az
 * 
 * Api/Mailservice/<service>.php fileban. A hozza tartozo config a config/<service>.php fileban talalhato.
 * 
 * Ez az osztaly olyan alap viselkedeseket definial, mint pl.:
 *  - Feliratkozas
 *  - Feliratkozas frissitese
 *  stb
 *  
 *  A konkret megvalositas, a konkret service osztaly feladata.
 * 
 * @author		Joó Martin <joomartin@jmweb.hu>
 * @date		2016-06-19
 * @package 	API
 * @copyright	Szabaduszok.com 2016
 * @since		2.0
 * @version		1.0
 */

abstract class Api_Mailservice
{
	// Lehetseges service tipusok
	const MAILMASTER	= 'mailmaster';
	const MAILCHIMP 	= 'mailchimp';
	
	/**
	 * @var null|Api_Mailservice	Singleton instance
	 */
	private static $_instance = null;
	
	/**
	 * @var string $_type Service tipusa (pl.: mailchimp)
	 */
	protected $_type = '';	
	/**
	 * @var Config_Group $_config Service -hez tartozo config
	 */
	protected $_config = [];
	/**
	 * @var string API hivasok alap URL -je
	 */
	protected $_url = '';
	
	public function __construct($type)
	{
		$this->_type 	= $type;
		$this->_config	= Kohana::$config->load($this->_type);
		$this->_url 	= $this->initUrl();
	}
	
	public function config() { return $this->_config; }
	public function type() { return $this->_type; }
	public function url() { return $this->_url; }
	
	/**
	 * Singleton instance
	 * 
         * @param mixed $class      Kert osztaly
         * @return Api_Mailservice  Peldany
	 */
	public static function instance($class = null)
	{
		if (self::$_instance)
		{
			return self::$_instance;
		}
		else
		{
			if (!$class)
			{
				$class = Kohana::$config->load('mailservice')->get('class');
			}	
			
			$fullClass = 'Api_Mailservice_' . ucfirst($class);
			self::$_instance = new $fullClass(strtolower($class));
			
			return self::$_instance;
		}								
	}
	
	/**
	 * Szabaduszo feliratasa listara
	 * @param Model_User $user
	 */
	abstract public function subscribeFreelancer(Model_User $user);
	
	/**
	 * Megbizo feliratasa listara
	 * @param Model_User $user
	 */
	abstract public function subscribeProjectowner(Model_User $user);
	
	/**
	 * Szabaduszo frissitese listan
	 * @param Model_User $user
	 */
	abstract public function updateFreelancer(Model_User $user);
	
	/**
	 * Megbizo frissitese listan
	 * @param Model_User $user
	 */
	abstract public function updateProjectowner(Model_User $user);
	
	/**
	 * Beallitja az API hivasok alap URL -jet
	 */
	abstract protected function initUrl();
	/**
	 * Hozzaadja a HTTP Basic hitelesiteshez szukseges headert, a kapott headerhez
	 *
	 * @param string	$header	Header string
	 * @return string	$header	Header string Auth -val kiegeszitve
	 */
	abstract protected function addAuthHeader($header);
	
	/**
	 * HTTP keres kuldese
	 *
	 * @param string 		$url	URL
	 * @param array 		$data	_POST adatok
	 * @param string|null	$header	Header string. Content-type nem kell, azt hozzateszi
	 * @param string|null	$method HTTP method
	 *
	 * @return mixed		Hivas valasza
	 */
	protected function sendRequest($url, $data, $header = null, $method = 'POST')
	{
		// Csak PRODUCTION
		if (Kohana::$environment == Kohana::PRODUCTION)
		{
			// Kiegeszites Content-type -val
			$header = ($header) ? $header : '';
			$header .= $header = "Content-type: application/json\r\n";
			
			$options = [
				'http' => [
					'header'  => $this->addAuthHeader($header),
					'method'  => $method,
					'content' => json_encode($data)
				]
			];
			
			$context  = stream_context_create($options);
			return file_get_contents($url, false, $context);
		}
		
		return true;
	}
}