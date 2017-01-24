<?php

abstract class Gateway_Mailinglist
{
    /**
     * @var Entity_User
     */
    protected $_user;

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
     * @param bool $isExists
     * @return bool mixed
     */
    public function add($isExists)
    {
        if ($isExists) {
            return $this->update();
        }

        return $this->subscribe();
    }

    /**
     * @return bool
     */
	abstract protected function subscribe();

    /**
     * @return bool
     */
    abstract protected function update();

    abstract protected function initConfig();
    abstract protected function initUrl();

    /**
     * @param string $header
     */
    abstract protected function addAuthHeader($header);

    /**
     * @param Entity_User $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

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
        /**
         * TODO torolni 
         */
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
			
			$context = stream_context_create($options);
			$content = @file_get_contents($url, false, $context);

            if (is_bool($content) && $content == false) {
                Log::instance()->add(Log::DEBUG, 'Mailinglist::sendRequest() method: ' . $method . ' content: ' . $content . ' data: ' . json_encode($data));
                return false;
            }
		}
		
		return true;
	}
}