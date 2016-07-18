<?php

class Api_Mailservice_Mailchimp extends Api_Mailservice
{
	const STATUS_SUBSCRIBED = 'subscribed';
	
	public function initUrl()
	{
		return 'https://' . $this->_config->get('dataCenter') . '.api.mailchimp.com/3.0/';
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
		
		$apiKey = $this->config()->get('apiKey');	
		$base64 = base64_encode('user:' . $apiKey);
		
		$auth = "Authorization: Basic " . $base64 .  "\r\n";
		
		$header .= $auth;		
		
		return $header;
	}
	
	/**
	 * Szabaduszo feliratasa listara
	 * @param Model_User $user
	 */
	public function subscribeFreelancer(Model_User $user)
	{
            $url = $this->_url . 'lists/' . $this->_config->get('freelancerListId') . '/members';		
            $data = $this->getFreelancerData($user);

            $this->sendRequest($url, $data);
            
            return true;
	}
	
	/**
	 * Megbizo feliratasa listara
	 * @param Model_User $user
	 */
	public function subscribeProjectowner(Model_User $user)
	{            
            $url = $this->_url . 'lists/' . $this->_config->get('projectOwnerListId') . '/members';		
            $data = $this->getProjectOwnerData($user);

            $this->sendRequest($url, $data);
            
            return true;
	}
	
	/**
	 * Szabaduszo frissitese listan
	 * @param Model_User $user
	 */
	public function updateFreelancer(Model_User $user)
	{
            $url = $this->_url . 'lists/' . $this->_config->get('freelancerListId') . '/members/' . md5(strtolower($user->email));		
            $data = $this->getFreelancerData($user);

            $this->sendRequest($url, $data, null, 'PATCH');
            
            return true;
	}
	
	/**
	 * Megbizo frissitese listan
	 * @param Model_User $user
	 */
	public function updateProjectowner(Model_User $user)
	{
            $url = $this->_url . 'lists/' . $this->_config->get('projectOwnerListId') . '/members/' . md5(strtolower($user->email));
            $data = $this->getProjectOwnerData($user);

            $this->sendRequest($url, $data, null, 'PATCH');
            
            return true;
	}
	
	/**
	 * Visszaadja a Szabaduszo adatait Mailchimp API formatumban
	 * 
	 * @param 	Model_User $user
	 * @return 	array
	 */
	protected function getFreelancerData(Model_User $user)
	{
		$data = [
			'email_address'		=> $user->email,
			'status'			=> self::STATUS_SUBSCRIBED,
			'merge_fields'		=> [
				'FIRSTNAME'		=> $user->firstname,
				'LASTNAME'		=> $user->lastname,
				'USER_ID'		=> $user->user_id,
				'ZIP'			=> $user->address_postal_code,
				'CITY'			=> $user->address_city,
				'STREET'		=> $user->address_street,
				'SLUG'			=> $user->slug,
				'HOURLY'		=> $user->min_net_hourly_wage,
				'SHORT'			=> $user->short_description,
				'INDUSTRIES' 	=> $user->getRelationString('industries'),
				'PROFS' 		=> $user->getRelationString('professions'),
				'SKILLS' 		=> $user->getRelationString('skills')
			],
		];
		
		return $this->getClearedData($data);
	}
	
	/**
	 * Visszaadja a Megbizo adatait Mailchimp API formatumban
	 * 
	 * @param 	Model_User $user
	 * @return 	array
	 */
	protected function getProjectOwnerData(Model_User $user)
	{
		$data = [
			'email_address'		=> $user->email,
			'status'			=> self::STATUS_SUBSCRIBED,
			'merge_fields'		=> [
				'FIRSTNAME'		=> $user->firstname,
				'LASTNAME'		=> $user->lastname,
				'USER_ID'		=> $user->user_id,
				'ZIP'			=> $user->address_postal_code,
				'CITY'			=> $user->address_city,
				'STREET'		=> $user->address_street,
				'PHONE'			=> $user->phonenumber,
				'SLUG'			=> $user->slug,				
				'SHORT'			=> $user->short_description,
				'INDUSTRIES' 	=> $user->getRelationString('industries'),
				'PROFS' 		=> $user->getRelationString('professions'),
				'IS_COMPANY'	=> (int) $user->is_company,
				'COMPANY'		=> $user->company_name
			],
		];
		
		return $this->getClearedData($data);	
	}
	
	protected function getClearedData(array $data)
	{
		$mergeFields = Arr::get($data, 'merge_fields');
		$result = [];
		
		foreach ($mergeFields as $key => $item)
		{
			$value = (is_null($item)) ? '' : $item;
			$result[$key] = $value;
		}
		
		$data['merge_fields'] = $result;
		
		return $data;
	}
}