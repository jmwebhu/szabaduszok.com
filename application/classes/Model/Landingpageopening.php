<?php

/**
 * class Model_Landingpageopening
*
* landing_page_openings tabla ORM osztalya
* Ez az osztaly felelos a landing oldalak szamolasaert, es a megnyitasok logolasaert
*
* @author Joó Martin <joomartin@jmweb.hu>
* @date 2016.05.19
*/

class Model_Landingpageopening extends ORM
{
	/**
	 * @var $_table_name ORM -hez tartozo tabla neve
	 */
	protected $_table_name = 'landing_page_openings';

	/**
	 * @var $_primary_key Elsodleges kulcs a tablaban
	 */
	protected $_primary_key = 'lpo_id';

	public function createOpening($lp_id, $date = null, $type = null)
	{
		try 
		{
			$result = ['error' => false];			
			
			$this->lpo_lp_id = $lp_id;
			$this->lpo_datetime = ($date) ? $date : date('Y-m-d H:i:s', time());
			$this->lpo_count = 1;
			$this->lpo_lp_type = $type;
			
			$this->save();
		} 
		catch (Exception $ex) 
		{
			ORM::factory('Log')->log('Landing oldal nyitas', 'Landing page opening', $ex);
			$result = [
				'error' 	=> true,
				'message'	=> 'Hiba'
			];
		}
		
		return $result;			
	}
}
