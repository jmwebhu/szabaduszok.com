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
    /**
     * @var Kohana_Config
     */
    protected $_config;

    /**
     * @var string
     */
    protected $_url;

    /**
     * @param Model_User $user
     */
	abstract public function subscribe(Model_User $user);

    /**
     * @param Model_User $user
     */
    abstract public function update(Model_User $user);

    abstract protected function initConfig();
    abstract protected function initUrl();

    /**
     * @param string $header
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
		if (Kohana::$environment == Kohana::PRODUCTION) {
			$header = ($header) ? $header : '';
			$header .= "Content-type: application/json\r\n";
			
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