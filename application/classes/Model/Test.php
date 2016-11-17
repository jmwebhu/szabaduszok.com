<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Test extends ORM
{	
	protected $_table_name      = 'test';
	protected $_primary_key     = 'id';
	
	protected $_table_columns = [
		'id'					=> ['type' => 'int', 'key' => 'PRI'],
		'password'					=> ['type' => 'string', 'null' => true],
        'salt'                      => ['type' => 'string', 'null' => true],
	];

    public function filters()
    {
        return array(
            'password' => array(
                array(array($this, 'testFilter'), array(':value', Model_Test::salt()))
            )
        );
    }   

    public function testFilter($password, $salt)
    {
        $this->salt = $salt;
        return Auth::instance()->hash($password . $salt);
    }

    public static function salt()
    {
        return openssl_random_pseudo_bytes(64);
    }
}