<?php

/**
 * class Model_Signup
 *
 * signups tabla ORM osztalya
 * Ez az osztaly felelos a feliratkozok kezeleseert
 *
 * @author Joó Martin <m4rt1n.j00@gmail.com>
 * @link https://docs.google.com/document/d/1vp-eK9MmS44o1XARQYg9z6nqWl1FhyErFHTObJ_Pyg8/edit#
 * @date 2016.07.12
 * @version 1.0
 * @since 2.1
 * @package Marketing
 */

class Model_Signup extends ORM
{
    protected $_table_name = 'signups';
    protected $_primary_key = 'signup_id';		

    protected $_created_column = [
        'column' => 'created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_updated_column = [
        'column' => 'updated_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_table_columns = [
        'signup_id'                             => ['type' => 'int', 'key' => 'PRI'],
        'email'					=> ['type' => 'string', 'null' => true],            
        'type'                             => ['type' => 'int',],
        'created_at'				=> ['type' => 'datetime', 'null' => true],
        'updated_at'				=> ['type' => 'datetime', 'null' => true],            
    ];
    
    public function createIfHasEmail($email = null, $type = 1)
    {
        if ($email) {
            $this->email    = $email;
            $this->type     = $type;
            
            $this->save();
        }
    }
    
    public function deleteIfExists($email)
    {
        $model  = new Model_Signup();
        $signup = $model->where('email', '=', $email)->limit(1)->find();

        $signup->delete();
        
        return true;
    }
}