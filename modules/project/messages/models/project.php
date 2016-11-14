<?php

return [
    'name' => [
        'not_empty' => 'A projekt nevét kérlek ne hagyd üresen',
    ],
    'user_id' => [
        'not_empty' => 'Nem sikerült a felhasználóhoz társítani a projektet, kérlek jelentkezz be újra',
    ],
    'short_description' => [
        'not_empty' => 'A rövid leírást kérlek ne hagyd üresen',
    ],
    'long_description' => [
        'not_empty' => 'A hosszú leírást kérlek ne hagyd üresen',
    ],
    'email' => [
        'not_empty'     => 'Az e-mailt kérlek ne hagyd üresen',
        'email'         => 'Kérlek helyes e-mail formátumot adj meg (@ és . is legyen benn)',
        'email_domain'  => 'Kérlek ellenőrizd az e-mail címhez tartozó domaint'
    ],
    'phonenumber' => [
        'not_empty' => 'A telefonszámot kérlek ne hagyd üresen'
    ],
    'salary_type' => [
        'not_empty' => 'A költségvetés típusa mezőt kérlek ne hagyd üresen',
    ],
    'salary_low' => [
        'not_empty' => 'A költségvetés alsó határa mezőt kérlek ne hagyd üresen',
        'numeric'   => 'A költségvetés alsó határa mezőben kérlek csak számokat használj (tizedes pont megengedett)',
    ],
    'salary_high'   => [
        'numeric'   => 'A költségvetés felső határa mezőben kérlek csak számokat használj (tizedes pont megengedett)',
    ]
];
