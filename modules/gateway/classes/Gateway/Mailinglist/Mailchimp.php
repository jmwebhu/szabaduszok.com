<?php

abstract class Gateway_Mailinglist_Mailchimp extends Gateway_Mailinglist
{
	const STATUS_SUBSCRIBED = 'subscribed';

    /**
     * @return array
     */
    abstract protected function getUserData();

    protected function initUrl()
	{
		$this->_url = 'https://' . $this->_config->get('dataCenter') . '.api.mailchimp.com/3.0/';
	}

    protected function initConfig()
    {
        $this->_config = Kohana::$config->load('mailchimp');
    }

    /**
	 * Hozzaadja a HTTP Basic hitelesiteshez szukseges headert, a kapott headerhez
	 * 
	 * @param string	$header	Header string
	 * @return string	$header	Header string Auth -val kiegeszitve 
	 */
	protected function addAuthHeader($header)
	{
		$header = ($header) ? $header : '';
		
		$apiKey = $this->_config->get('apiKey');
		$base64 = base64_encode('user:' . $apiKey);
		
		$auth = "Authorization: Basic " . $base64 .  "\r\n";
		
		$header .= $auth;		
		
		return $header;
	}

    /**
     * @return array
     */
	protected function getClearedUserData()
    {
        $data = $this->getUserData();
        return $this->getClearedData($data);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getClearedData(array $data)
    {
        $mergeFields    = Arr::get($data, 'merge_fields');
        $result         = [];

        foreach ($mergeFields as $key => $item) {
            $value          = (is_null($item)) ? '' : $item;
            $result[$key]   = $value;
        }

        $data['merge_fields'] = $result;

        return $data;
    }
}