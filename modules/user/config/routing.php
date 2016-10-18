<?php

Route::set('freelancerRegistration', 'szabaduszo-regisztracio')
	->defaults(array(
		'controller' => 'User_Create_Freelancer',
		'action'     => 'index',
));

Route::set('projectOwnerRegistration', 'megbizo-regisztracio')
	->defaults(array(
		'controller' => 'User_Create_Employer',
		'action'     => 'index',
));
	
Route::set('projectOwnerProfile', 'megbizo/<slug>')
	->defaults([
		'controller'    => 'User',
		'action'        => 'projectownerprofile'
	]);

Route::set('freelancerProfile', 'szabaduszo/<slug>')
->defaults([
		'controller'    => 'User',
		'action'        => 'freelancerprofile'
]);

Route::set('freelancers', 'szabaduszok(/<page>)')
	->defaults([
		'controller'    => 'User',
		'action'        => 'freelancers'
]);
	
Route::set('freelancerProfileEdit', 'szabaduszo-profil-szerkesztes/<slug>')
	->defaults(array(
		'controller' => 'User',
		'action'     => 'freelancerprofileedit',
	));
	
Route::set('projectOwnerProfileEdit', 'megbizo-profil-szerkesztes/<slug>')
	->defaults(array(
		'controller' => 'User',
		'action'     => 'projectownerprofileedit',
	));

Route::set('login', 'szabaduszok-belepes')
	->defaults(array(
		'controller' => 'User',
		'action'     => 'login',
	));
	
Route::set('logout', 'szabaduszok-kilepes')
	->defaults(array(
		'controller' => 'User',
		'action'     => 'logout',
	));
	

Route::set('passwordReminder', 'szabaduszok-elfelejtett-jelszo')
	->defaults(array(
		'controller' => 'User',
		'action'     => 'passwordreminder',
	));

Route::set('userPasswordreminder', 'szabaduszok-jelszo-emlekezteto')
    ->defaults([
        'controller'    => 'User',
        'action'        => 'passwordreminder'
    ]);

Route::set('userAjax', 'user/ajax/<actiontarget>(/<maintarget>)')
    ->defaults([
        'controller'    => 'User',
        'action'        => 'ajax'
    ]);
