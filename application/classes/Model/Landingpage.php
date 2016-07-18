<?php

/**
 * class Model_Landingpage
 *
 * landing_pages tabla ORM osztalya
 * Ez az osztaly felelos a landing oldalakert
 *
 * @author Joó Martin <joomartin@jmweb.hu>
 * @date 2016.05.14
 */

class Model_Landingpage extends ORM
{
    /**
     * @var $_table_name ORM -hez tartozo tabla neve
     */
    protected $_table_name = 'landing_pages';

    /**
     * @var $_primary_key Elsodleges kulcs a tablaban
     */
    protected $_primary_key = 'lp_id';

    /**
     * Noveli a kapott tipushoz tartozo szamlalo erteket
     * 
     * @param string $type	Tipus
     * @return array		Muvelet eredmenye. Hiba eseten error_logs bejegyzes
     */
    public function increase($type)
    {
    	try 
    	{
    		$result = ['error' => false];
    		$lp = $this->getOrCreateByType($type);
    		
    		// Regi ertek novelese
    		$counter = intval(Arr::get($lp, 'lp_counter')) + 1;
    		
    		$values = ['lp_counter' => $counter];    		
    		$update = DB::update($this->_table_name)->set($values)->where('lp_id', '=', Arr::get($lp, 'lp_id'))->execute();
    		
    		// Opening letrehozas
    		$lpo = new Model_Landingpageopening();
    		$lpo->createOpening(Arr::get($lp, 'lp_id'), null, Arr::get($lp, 'lp_type'));
    		
    		
    		$result['lp_counter'] = $counter;
    	} 
    	catch (Exception $ex) 
    	{
    		ORM::factory('Errorlog')->log('Landing oldal szamolas', 'Landing oldal szamolas', $ex);
    		
    		$result = [
    			'error' 	=> true,
    			'message'	=> 'Hiba'
    		];
    	}
    	
    	return $result;
    }
    
    /**
     * Visszaadja a kapott tipushoz tartozo szamlalot. Ha nem talalhato, letrehoz egy ujat
     * 
     * @param string $type	Landing oldal tipusa
     * @return array		Landing oldal szamlalo adatai. Hiba eseten error_logs bejegyzes
     */
    public function getOrCreateByType($type)
    {
    	try 
    	{	
    		$lp = $this->getByType($type);
    		 
    		// Ha nincs szamlalo a kapott tipushoz
    		if (!isset($lp['lp_id']))
    		{
    			// Letrehoz egy ujat
    			$values = [
    				'lp_id'		=> null,
    				'lp_type'		=> $type,
    				'lp_counter'	=> 0
    			];
    		
    			$fields = array_keys($values);    		
    			$insert = DB::insert($this->_table_name, $fields)->values($values)->execute();
    			
    			// Beszuras utan lekerdezi
    			$lp = DB::select()->from($this->_table_name)->where('lp_id', '=', Arr::get($insert, 0))->execute()->current();    	    		
    		}    		
    	} 
    	catch (Exception $ex) 
    	{    	
    		ORM::factory('Errorlog')->log('Landing oldal szamolas', 'Landing oldal szamolas', $ex);
    	}
    	
    	return $lp;
    }
    
    /**
     * Visszaadja a kapott tipushoz tartozo szamlalot.
     *
     * @param string $type	Landing oldal tipusa
     * @return array		Landing oldal szamlalo adatai
     */
    public function getByType($type)
    {
    	// Kapott tipushoz tartozo szamlalo lekerdezese
    	return DB::select()
	    	->from($this->_table_name)
	    	->where('lp_type', '=', $type)
	    	->execute()->current();
    }
}
