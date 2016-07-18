<?php

/**
 * class Model_Landing
 *
 * landing_pages tabla ORM osztalya
 * Ez az osztaly felelos a landing oldalak statisztikajaert
 *
 * @author  Joó Martin <joomartin@jmweb.hu>
 * @link    https://docs.google.com/document/d/1vp-eK9MmS44o1XARQYg9z6nqWl1FhyErFHTObJ_Pyg8/edit#
 * @date    2016.07.07
 * @since   2.1
 * @package Marketing
 */

class Model_Landing extends ORM
{	
    protected $_table_columns = [
    	'landing_page_id'      => ['type' => 'int', 'key' => 'PRI'],
        'type'                 => ['type' => 'int'],
        'counter'              => ['type' => 'int'],
        'name'                 => ['type' => 'string', 'null' => true]
    ];    

    /**
     * @var string $_table_name ORM -hez tartozo tabla neve
     */
    protected $_table_name = 'landing_pages';

    /**
     * @var string $_primary_key Elsodleges kulcs a tablaban
     */
    protected $_primary_key = 'landing_page_id';

    /**
     * @var array $_belongs_to N - 1 es 1 - 1 kapcsolatokat definialja
     */
    protected $_belongs_to = [];
    
    protected $_has_many = [
        'openinigs' => [
            'model'         => 'Landing_Page_Opening',
            'far_key'       => 'landing_page_opening_id',
            'foreign_key'   => 'landing_page_id',
        ],
    ];
    
    /**
     * Landing oldal megnyitasakor hivodik meg. Noveli megnyitasok szamat.
     * Ha nincs meg ilyen landing oldal, akkor letrehozza
     *
     * @param string $name   _POST nev
     */
    public function open($name)
    {        
        // Nincs ilyen landing oldal
        if (!$this->loaded())
        {
            $post = [
                'name'  => $name
            ];
            
            // Letrehoz egy ujat
            $submit = $this->submit($post);

            if (Arr::get($submit, 'error'))
            {
                return ['error' => true];
            }
        }

        // Szamlalo noveles
        $this->counter++;
        $save = $this->save();        

        // Megnyitas log
        $opening = new Model_Landing_Page_Opening();
        $opening->landing   = $this;
        $opening->datetime  = date('Y-m-d H:i', time());

        $opening->save();

        return ['error' => false];
    }
}
