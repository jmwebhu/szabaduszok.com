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
    'profile_picture_path' => [
        'not_empty'     => 'Hibás képformátum, kérlek próbáld meg újra. JPG vagy PNG az elfogadott.'
    ],
    'cv_path' => [
        'not_empty'     => 'Hibás önéletrajz formátum, kérlek próbáld meg újra.  .pdf, .doc, .docx, .pages, .odt az elfogadott.'
    ]
];