<?php

/**
 * class Model_Leadmagnet
 *
 * lead_magnets tabla ORM osztalya
 * Ez az osztaly felelos a lead magnet file -okert
 *
 * @author Joó Martin <joomartin@jmweb.hu>
 * @date 2016.07.12
 * @version 1.0
 * @since 2.1
 * @package Marketing
 */

class Model_Leadmagnet extends ORM
{
    /**
     * @var $_table_name ORM -hez tartozo tabla neve
     */
    protected $_table_name = 'lead_magnets';

    /**
     * @var $_primary_key Elsodleges kulcs a tablaban
     */
    protected $_primary_key = 'lead_magnet_id';
    
    /**
     * @var string $_url    Teljes URL, ahol le lehet tolteni
     */
    protected $_url = '';
    
    public function getUrl() { return $this->_url; }
    
    protected $_table_columns = [
        'lead_magnet_id'        => ['type' => 'int','key' => 'PRI'],
        'path'      		=> ['type' => 'string', 'null' => true],
        'name'      		=> ['type' => 'string', 'null' => true],
        'is_current'            => ['type' => 'int', 'null' => true]
    ];

    /**
     * Visszaadja az aktualis lead magnet URL -jet, amit lehet kuldeni e-mailben
     * Tipusonkent dolgozik
     * 
     * @param integer $type     Felhasznalo tipus. 1 = Szabaduszo, 2 = Megbizo
     * @return Model_Leadmagnet
     */
    public function getCurrentByType($type = 1)
    {
        $this->where('is_current', '=', 1)->and_where('type', '=', $type)->limit(1)->find();
        $baseUrl = Kohana::$config->load('base')->get('base_url');
        
        $this->_url = $baseUrl . DIRECTORY_SEPARATOR . 'leadmagnets' . DIRECTORY_SEPARATOR . $this->path;
        
        return $this;
    }

    /**
     * @param string $email
     * @param int $type
     */
    public static function sendTo($email, $type)
    {
        $model      = new Model_Leadmagnet();
        $leadmagnet = $model->getCurrentByType($type);

        if ($leadmagnet->loaded()) {
            $html = Twig::getHtmlFromTemplate('Templates/leadMagnet.twig', [
                'leadmagnet'    => $leadmagnet,
            ]);

            Email::send($email, '[ESETTANULMÁNY]', $html);
        }
    }
}
