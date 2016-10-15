<?php

abstract class Gateway_Mailinglist
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
     * Gateway_Mailinglist constructor.
     */
    protected function __construct()
    {
        $this->initConfig();
        $this->initUrl();
    }

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