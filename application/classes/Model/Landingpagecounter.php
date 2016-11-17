<?php

/**
 * class Model_Landingpagecounter
 *
 * landing_page_counter tabla ORM osztalya
 * Ez az osztaly felelos a landing oldalak megjelenitesenek szamolasaert
 *
 * @author Joó Martin <joomartin@jmweb.hu>
 * @date 2016.05.14
 */

class Model_Landingpagecounter extends ORM
{
    /**
     * @var $_table_name ORM -hez tartozo tabla neve
     */
    protected $_table_name = 'landing_page_counters';

    /**
     * @var $_primary_key Elsodleges kulcs a tablaban
     */
    protected $_primary_key = 'lpc_id';

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
    		$lpc = $this->getOrCreateByType($type);
    		
    		// Regi ertek novelese
    		$counter = intval(Arr::get($lpc, 'lpc_counter')) + 1;
    		
    		$values = ['lpc_counter' => $counter];    		
    		$update = DB::update($this->_table_name)->set($values)->where('lpc_id', '=', Arr::get($lpc, 'lpc_id'))->execute();
    		
    		$result['lpc_counter'] = $counter;
    	} 
    	catch (Exception $ex) 
    	{
    		Log::instance()->addException($ex);
    		
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
    		$lpc = $this->getByType($type);
    		 
    		// Ha nincs szamlalo a kapott tipushoz
    		if (!isset($lpc['lpc_id']))
    		{
    			// Letrehoz egy ujat
    			$values = [
    				'lpc_id'		=> null,
    				'lpc_type'		=> $type,
    				'lpc_counter'	=> 0
    			];
    		
    			$fields = array_keys($values);    		
    			$insert = DB::insert($this->_table_name, $fields)->values($values)->execute();
    			
    			// Beszuras utan lekerdezi
    			$lpc = DB::select()->from($this->_table_name)->where('lpc_id', '=', Arr::get($insert, 0))->execute()->current();    	    		
    		}    		
    	} 
    	catch (Exception $ex) 
    	{    	
    		Log::instance()->addException($ex);
    	}
    	
    	return $lpc;
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
	    	->where('lpc_type', '=', $type)
	    	->execute()->current();
    }
}
