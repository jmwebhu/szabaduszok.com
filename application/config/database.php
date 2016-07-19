<?php defined('SYSPATH') OR die('No direct access allowed.');

$baseconfig = include "base.php";

return array
(
	'default' => array
	(
		'type'       => 'MySQL',
		'connection' => array(
			/**
			 * The following options are available for MySQL:
			 *
			 * string   hostname     server hostname, or socket
			 * string   database     database name
			 * string   username     database username
			 * string   password     database password
			 * boolean  persistent   use persistent connections?
			 * array    variables    system variables as "key => value" pairs
			 *
			 * Ports and sockets may be appended to the hostname.
			 */
			'hostname'   => '127.0.0.1',
			'database'   => $baseconfig['db'],
			'username'   => $baseconfig['db_user'],
			'password'   => $baseconfig['db_password'],
			'persistent' => FALSE,
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
	),
        'test' => array
	(
		/**
		 * Unittest teszt adatbazis
		 */
                'type'       => 'MySQL',
                'connection' => array(
                    'hostname'   => '127.0.0.1',
                    'database'   => 'test.' . $baseconfig['db'],
                    'username'   => 'root',
                    'password'   => '',
                    'persistent' => FALSE,
                ),
                'table_prefix' => '',
                'charset'      => 'utf8',
                'caching'      => FALSE,
	),
	'v1' => array
	(
			'type'       => 'MySQL',
			'connection' => array(
					/**
					 * The following options are available for MySQL:
	*
	* string   hostname     server hostname, or socket
	* string   database     database name
	* string   username     database username
	* string   password     database password
	* boolean  persistent   use persistent connections?
	* array    variables    system variables as "key => value" pairs
	*
	* Ports and sockets may be appended to the hostname.
	*/
					'hostname'   => '127.0.0.1',
					'database'   => 'szabaduszok.com_production',
					'username'   => 'root',
					'password'   => '',
					'persistent' => FALSE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => FALSE,
	),
	'alternate' => array(
		'type'       => 'PDO',
		'connection' => array(
			/**
			 * The following options are available for PDO:
			 *
			 * string   dsn         Data Source Name
			 * string   username    database username
			 * string   password    database password
			 * boolean  persistent  use persistent connections?
			 */
			'dsn'        => 'mysql:host=localhost;dbname=kohana',
			'username'   => 'root',
			'password'   => 'r00tdb',
			'persistent' => FALSE,
		),
		/**
		 * The following extra options are available for PDO:
		 *
		 * string   identifier  set the escaping identifier
		 */
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
	),
	/**
	 * MySQLi driver config information
	 *
	 * The following options are available for MySQLi:
	 *
	 * string   hostname     server hostname, or socket
	 * string   database     database name
	 * string   username     database username
	 * string   password     database password
	 * boolean  persistent   use persistent connections?
	 * array    ssl          ssl parameters as "key => value" pairs.
	 *                       Available keys: client_key_path, client_cert_path, ca_cert_path, ca_dir_path, cipher
	 * array    variables    system variables as "key => value" pairs
	 *
	 * Ports and sockets may be appended to the hostname.
	 *
	 * MySQLi driver config example:
	 *
	 */
// 	'alternate_mysqli' => array
// 	(
// 		'type'       => 'MySQLi',
// 		'connection' => array(
// 			'hostname'   => 'localhost',
// 			'database'   => 'kohana',
// 			'username'   => FALSE,
// 			'password'   => FALSE,
// 			'persistent' => FALSE,
// 			'ssl'        => NULL,
// 		),
// 		'table_prefix' => '',
// 		'charset'      => 'utf8',
// 		'caching'      => FALSE,
// 	),
);
