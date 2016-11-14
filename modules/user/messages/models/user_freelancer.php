<?php

$file = Kohana::find_file('models/user.php');
$user = include $file;

$freelancer = [
	'min_net_hourly_wage'		=> [
		'not_empty'	=> 'A minimum nettó órabért kérlek ne hagyd üresen',
		'numeric'	=> 'A minimum nettó órabérnél kérlek csak számokat használj'
	],
	'webpage'		=> [
		'url' 		=> 'A weboldalnál kérlek érvényes URL -t adj meg'
	]
];

return array_merge($user, $freelancer);