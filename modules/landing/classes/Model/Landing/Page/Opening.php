<?php

/**
 * class Model_Landing_Page_Opening
 *
 * landing_page_openings tabla ORM osztalya
 * Ez az osztaly felelos a landing oldalak megnyitasanak statisztikaert
 *
 * @author  Joó Martin <joomartin@jmweb.hu>
 * @link    https://docs.google.com/document/d/1vp-eK9MmS44o1XARQYg9z6nqWl1FhyErFHTObJ_Pyg8/edit#
 * @date    2016.07.07
 * @since   2.1
 * @package Marketing
 */

class Model_Landing_Page_Opening extends ORM
{	
    protected $_table_columns = [
    	'landing_page_opening_id'  => ['type' => 'int', 'key' => 'PRI'],
        'landing_page_id'          => ['type' => 'int'],
        'datetime'                 => ['type' => 'datetime']
    ];    

    /**
     * @var string $_table_name ORM -hez tartozo tabla neve
     */
    protected $_table_name = 'landing_page_openings';

    /**
     * @var string $_primary_key Elsodleges kulcs a tablaban
     */
    protected $_primary_key = 'landing_page_opening_id';

    /**
     * @var array $_belongs_to N - 1 es 1 - 1 kapcsolatokat definialja
     */
    protected $_belongs_to = [
        'landing' => [
            'model'         => 'Landing',
            'foreign_key'   => 'landing_page_id'
        ]
    ];
    
    protected $_has_many = [];
    
    /**
     * Landing oldal megnyitasakor hivodik meg. Noveli megnyitasok szamat.
     */
    public function open()
    {
        $this->counter++;
        $this->save();

        
    }
}
