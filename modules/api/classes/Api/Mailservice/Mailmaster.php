<?php

class Api_Mailmaster
{	
	protected $_type = 'mailmaster';
	
	/**
	 * Duplikalt e-mail cim eseten valasz kod
	 * @var DUPLICATE_EMAIL_ERROR
	 */
	const DUPLICATE_EMAIL_ERROR = -1;
	/**
	 * Hibas e-mail cim eseten valasz kod
	 * @var EMAIL_SYNTAX_ERROR
	 */
	const EMAIL_SYNTAX_ERROR 	= -2;
	/**
	 * Ismeretlen hiba eseten valasz kod
	 * @var UNKNOWN_ERROR
	 */
	const UNKNOWN_ERROR 		= 0;
	
	/**
	 * Feliratja a szabadiszot a configban levo listara
	 * 
	 * @param Model_User $user		Felhasznalo
	 * @return mixed				API hivas eredmenye. Siker eseten mm_id, egyebkent self hibakodok
	 * 
	 * @throws Exception			Barmilyen hiba eseten
	 */
	public function subscribeFreelancer(Model_User $user)
	{
		try
		{
			$result = false;
			
			// Csak production eseten
			if (Kohana::$environment == Kohana::PRODUCTION)
			{
				$config = Kohana::$config->load('mailmaster');
				$url = "http://" . $config->get('username') . ":" . $config->get('password') . "@restapi.emesz.com/subscribe/" . $config->get('freelancerListId') . "/form/" . $config->get('freelancerFormId');				
					
				$data = $this->getFreelancerData($user);					
				$result = $this->sendPostRequest($url, $data);
					
				// Hibas valasz eseten kivetel
				if ($result === false || in_array($result, [self::DUPLICATE_EMAIL_ERROR, self::EMAIL_SYNTAX_ERROR, self::UNKNOWN_ERROR]))
				{
					throw new Exception('Mailmaster API Szabadúszó feliratkozas. Hiba kód: ' . $result);
				}
			}
		}
		catch (Exception $ex)
		{
			$errorLog = new Model_Errorlog();
			$errorLog->log($ex);
			
			$result = false;
		}				
		
		return $result;
	}
	
	/**
	 * Feliratja a Megbizot a configban levo listara
	 * 
	 * @param Model_User $user		Felhasznalo
	 * @return mixed				API hivas eredmenye. Siker eseten mm_id, egyebkent self hibakodok
	 * 
	 * @throws Exception			Barmilyen hiba eseten
	 */
	public function subscribeProjectowner(Model_User $user)
	{
		try
		{
			$result = false;
				
			// Csak production eseten
			if (Kohana::$environment == Kohana::PRODUCTION)
			{
				$config = Kohana::$config->load('mailmaster');
				$url = "http://" . $config->get('username') . ":" . $config->get('password') . "@restapi.emesz.com/subscribe/" . $config->get('projectownerListId') . "/form/" . $config->get('projectownerFormId');					
					
				$data = $this->getProjectownerData($user);					
				$result = $this->sendPostRequest($url, $data);				
					
				// Hibas valasz eseten kivetel
				if ($result === false || in_array($result, [self::DUPLICATE_EMAIL_ERROR, self::EMAIL_SYNTAX_ERROR, self::UNKNOWN_ERROR]))
				{
					throw new Exception('Mailmaster API Megbizo feliratkozas. Hiba kód: ' . $result);
				}
			}
		}
		catch (Exception $ex)
		{
			$errorLog = new Model_Errorlog();
			$errorLog->log($ex);
				
			$result = false;
		}
	
		return $result;
	}
	
	/**
	 * Feliratja a szabadiszot a configban levo listara
	 *
	 * @param Model_User $user		Felhasznalo
	 * @return mixed				API hivas eredmenye. Siker eseten mm_id, egyebkent self hibakodok
	 *
	 * @throws Exception			Barmilyen hiba eseten
	 */
	public function updateFreelancer(Model_User $user)
	{
		try
		{
			$result = false;
				
			// Csak production eseten
			if (Kohana::$environment == Kohana::DEVELOPMENT)
			{
				$config = Kohana::$config->load('mailmaster');
				$url = "http://" . $config->get('username') . ":" . $config->get('password') . "@restapi.emesz.com/update/" . $config->get('freelancerListId') . "/form/" . $config->get('freelancerUpdateFormId') . "/record/" . $user->mm_id;
					
				$data = $this->getFreelancerData($user);									
				$result = $this->sendPostRequest($url, $data);
					
				// Hibas valasz eseten kivetel
				if ($result === false || in_array($result, [self::DUPLICATE_EMAIL_ERROR, self::EMAIL_SYNTAX_ERROR, self::UNKNOWN_ERROR]))
				{
					throw new Exception('Mailmaster API Szabadúszó adatmódosítás. Hiba kód: ' . $result);
				}
			}
		}
		catch (Exception $ex)
		{
			$errorLog = new Model_Errorlog();
			$errorLog->log($ex);
				
			$result = false;
		}
	
		return $result;
	}
	
	/**
	 * Modositja a Megbizot a configban levo listam
	 *
	 * @param Model_User $user		Felhasznalo
	 * @return mixed				API hivas eredmenye. Siker eseten mm_id, egyebkent self hibakodok
	 *
	 * @throws Exception			Barmilyen hiba eseten
	 */
	public function updateProjectowner(Model_User $user)
	{
		try
		{		
			$result = false;
				
			// Csak production eseten
			if (Kohana::$environment == Kohana::DEVELOPMENT)
			{
				$config = Kohana::$config->load('mailmaster');
				$url = "http://" . $config->get('username') . ":" . $config->get('password') . "@restapi.emesz.com/update/" . $config->get('projectownerListId') . "/form/" . $config->get('projectownerUpdateFormId') . "/record/" . $user->mm_id;
					
				$data = $this->getProjectownerData($user);
				$result = $this->sendPostRequest($url, $data);
					
				// Hibas valasz eseten kivetel
				if ($result === false || in_array($result, [self::DUPLICATE_EMAIL_ERROR, self::EMAIL_SYNTAX_ERROR, self::UNKNOWN_ERROR]))
				{
					throw new Exception('Mailmaster API Megbizo adatmódosítás. Hiba kód: ' . $result);
				}
			}
		}
		catch (Exception $ex)
		{
			$errorLog = new Model_Errorlog();
			$errorLog->log($ex);
				
			$result = false;
		}
	
		return $result;
	}
	
	/**
	 * Visszaadja a user objektumbol az adatokat MailMaster Api szamara
	 * 
	 * @param Model_User $user
	 * @return array
	 */
	protected function getFreelancerData(Model_User $user)
	{
		return [
			'mssys_lastname' 				=> $user->lastname,
			'mssys_firstname' 				=> $user->firstname,
			'email' 						=> $user->email,
			'mssys_bill_zip' 				=> $user->address_postal_code,
			'mssys_bill_city' 				=> $user->address_city,
			'mssys_bill_address' 			=> $user->address_street,
			'rovid_szakmai_bemutatkozas'	=> $user->short_description,
			'minimum_netto_oraber' 			=> $user->min_net_hourly_wage,
			'slug'							=> $user->slug,
			'iparagak' 						=> $user->getRelationString('industries'),
			'szakteruletek' 				=> $user->getRelationString('professions'),
			'kepessegek' 					=> $user->getRelationString('skills'),
		];
	}
	
	/**
	 * Visszaadja a user objektumbol az adatokat MailMaster Api szamara
	 *
	 * @param Model_User $user
	 * @return array
	 */
	protected function getProjectownerData(Model_User $user)
	{
		return [
			'mssys_lastname' 				=> $user->lastname,
			'mssys_firstname' 				=> $user->firstname,
			'email' 						=> $user->email,
			'mssys_bill_zip' 				=> $user->address_postal_code,
			'mssys_bill_city' 				=> $user->address_city,
			'mssys_bill_address' 			=> $user->address_street,
			'rovid_szakmai_bemutatkozas'	=> $user->short_description,
			'slug'							=> $user->slug,
			'iparagak' 						=> $user->getRelationString('industries'),
			'szakteruletek' 				=> $user->getRelationString('professions'),
			'ceges'							=> $user->is_company,
			'mssys_company'					=> $user->company_name,
			'mssys_phone'					=> $user->phonenumber
		];
	}
	
	
}