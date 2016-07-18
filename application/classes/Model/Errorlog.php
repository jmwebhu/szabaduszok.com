<?php

/**
 * class Model_Errorlog
 *
 * error_logs tabla ORM osztalya
 * Ez az osztaly felelos a hibak logolasaert
 *
 * @author Joó Martin <m4rt1n.j00@gmail.com>
 * @link https://docs.google.com/document/d/1vp-eK9MmS44o1XARQYg9z6nqWl1FhyErFHTObJ_Pyg8/edit#
 * @date 2016.03.18
 */

class Model_Errorlog extends ORM
{
	protected $_created_column = [
		'column' => 'created_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_table_columns = [
		'error_log_id'	=> ['type' => 'int', 'key' => 'PRI'],
		'message'		=> ['type' => 'string', 'null' => true],
		'trace' 		=> ['type' => 'string', 'null' => true],
		'created_at'	=> ['type' => 'datetime', 'null' => true],
	];
	
    /**
     * @var $_table_name ORM -hez tartozo tabla neve
     */
    protected $_table_name = 'error_logs';

    /**
     * @var $_primary_key Elsodleges kulcs a tablaban
     */
    protected $_primary_key = 'error_log_id';

    /**
     * Letrehoz egy uj error_log -ot.    
     *
     * @param Exception $ex     Kivetel objektum
     * @return bool 			Eredmeny
     */
    public function log(Exception $ex)
    {
        $result = true;
        try
        {
            $this->message = $ex->getMessage() . ' URL: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $this->trace = $ex->getTraceAsString();
            
            $this->save();
            
            if (Kohana::$environment == Kohana::DEVELOPMENT)
            {
                throw new Kohana_Exception($ex->getMessage());
            	echo Debug::vars($ex);
            	exit;
            }
        }
        catch (Exception $ex)
        {        	
            $result = false;
        }
        
        return $result;
    }    
}
