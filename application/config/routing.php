<?php

Route::set('home', '')
	->defaults(array(
		'controller' => 'Main',
		'action'     => 'index',
	));
	
Route::set('interested', 'erdekel')
	->defaults(array(
		'controller' => 'Main',
		'action'     => 'interested',
	));
	
Route::set('howItWorks', 'szabaduszok-hogyan-mukodik')
	->defaults(array(
		'controller' => 'Main',
		'action'     => 'howitworks',
	));
	
Route::set('contacUs', 'szabaduszok-irj-nekunk')
	->defaults(array(
		'controller' => 'Main',
		'action'     => 'contactus',
	));

Route::set('contact', 'szabaduszok-kapcsolat')
	->defaults(array(
			'controller' => 'Main',
			'action'     => 'contact',
	));

Route::set('termsofuse', 'szabaduszok-felhasznalasi-feltetelek')
	->defaults(array(
			'controller' => 'Main',
			'action'     => 'termsofuse',
	));

Route::set('privacy', 'szabaduszok-adatvedelem')
	->defaults(array(
			'controller' => 'Main',
			'action'     => 'privacy',
	));

Route::set('test', 'main/test')
	->defaults(array(
		'controller' => 'Main',
		'action'     => 'test',
));

Route::set('migrateUsers', 'migrate/users')
	->defaults(array(
		'controller' => 'Migrate',
		'action'     => 'users',
));
	
Route::set('migrateSignups', 'migrate/signups')
	->defaults(array(
		'controller' => 'Migrate',
		'action'     => 'signups',
));
	
Route::set('migrateSetNames', 'migrate/setnames')
	->defaults(array(
		'controller' => 'Migrate',
		'action'     => 'setnames',
));
	
Route::set('migrateSearchtext', 'migrate/searchtext')
->defaults(array(
		'controller' => 'Migrate',
		'action'     => 'searchtext',
));

Route::set('migrateUserpassword', 'migrate/userpassword')
->defaults(array(
		'controller' => 'Migrate',
		'action'     => 'userpassword',
));

Route::set('migrateSlug', 'migrate/slug')
->defaults(array(
		'controller' => 'Migrate',
		'action'     => 'slug',
));

Route::set('testIndex', 'test/index')
->defaults(array(
		'controller' => 'Test',
		'action'     => 'index',
));

Route::set('testClearcache', 'test/clearcache')
->defaults(array(
	'controller' => 'Test',
	'action'     => 'clearcache',
));