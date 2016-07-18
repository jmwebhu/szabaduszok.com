<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * class Model_Profile
 * 
 * profiles tabla ORM osztaly. Kulcso profilokat tarol, es kezel, pl LinkedIn
 * 
 * @author		Joó Martin <joomartin@jmweb.hu>
 * @package		user
 * @since		2.1
 * @version		1.0
 * @link		https://www.dropbox.com/s/7d49u7sl7fu9cs9/Fejleszt%C3%A9si%20le%C3%ADr%C3%A1s%2C%20rendszerterv.pages?dl=0
 * 
 * 2016.07.18
 */

class Model_Profile extends ORM
{		
	protected $_table_name = 'profiles';
	protected $_primary_key = 'profile_id';		
	
	protected $_table_columns = [
		'profile_id'		=> ['type' => 'int', 'key' => 'PRI'],
		'name'				=> ['type' => 'string', 'null' => true],
		'slug'				=> ['type' => 'string', 'null' => true],
		'icon'				=> ['type' => 'string', 'null' => true],
		'icon_type'			=> ['type' => 'string', 'null' => true],
		'icon_type_group'	=> ['type' => 'string', 'null' => true],
		'base_url'			=> ['type' => 'string', 'null' => true],
		'is_active'			=> ['type' => 'int', 'null' => true]		
	];
	
    protected $_has_many = [
        'users' => [
			'model'         => 'User',
			'through'		=> 'users_profiles',
			'far_key'		=> 'user_id',
			'foreign_key'	=> 'profile_id',
		],	  	
    ]; 
    
	

} // End Profile Model
