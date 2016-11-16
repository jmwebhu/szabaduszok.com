<?php

$file = Kohana::find_file('messages/models', 'user');
$user = include $file;

$user['address_postal_code']['not_empty'] = 'Az irányítószámot kérlek ne hagyd üresen';

$employer = [
	'address_city'		=> [
		'not_empty'	=> 'A várost kérlek ne hagyd üresen',
		'alpha'		=> 'A város kérlek csak betűket használj'
	],
	'phone'		=> [
		'not_empty' => 'A telefonszámot kérlek ne hagyd üresen'
	]
];

return array_merge($user, $employer);