<?php

return [
	'lastname'		=> [
		'not_empty'	=> 'A vezetéknevet kérlek ne hagyd üresen',
		'alpha'		=> 'A vezetéknévben kérlek csak betűket használj'
	],
	'firstname'		=> [
		'not_empty'	=> 'A keresztnevet kérlek ne hagyd üresen',
		'alpha'		=> 'A keresztnévben kérlek csak betűket használj'
	],
	'email' => [
        'not_empty'     => 'Az e-mailt kérlek ne hagyd üresen',
        'email'         => 'Kérlek helyes e-mail formátumot adj meg (@ és . is legyen benn)',
        'email_domain'  => 'Kérlek ellenőrizd az e-mail címhez tartozó domaint'
    ],
    'address_postal_code' => [
        'numeric'     	=> 'Az irányítószám csak számokat tartalmazzon',
        'min_length'	=> 'Az irányítószám legyen legalább 4 számjegy'       
    ],
];