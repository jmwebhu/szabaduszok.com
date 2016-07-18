<?php

/**
 * class Model_Sendedemail
 *
 * sende_emails tabla ORM osztalya
 * Ez az osztaly felelos a kikuldott e-mailek logolasaert
 *
 * @author Joó Martin <m4rt1n.j00@gmail.com>
 * @link https://docs.google.com/document/d/1vp-eK9MmS44o1XARQYg9z6nqWl1FhyErFHTObJ_Pyg8/edit#
 * @date 2016.03.18
 */

class Model_Sendedemail extends ORM
{
    /**
     * @var $_table_name ORM -hez tartozo tabla neve
     */
    protected $_table_name = 'sended_emails';

    /**
     * @var $_primary_key Elsodleges kulcs a tablaban
     */
    protected $_primary_key = 'se_id';    
}
