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

Route::set('migrateMergeTags', 'migrate/mergetags')
->defaults(array(
		'controller' => 'Migrate',
		'action'     => 'mergetags',
));

Route::set('migrateUserProjectNotifications', 'migrate/updateuserprojectnotifications')
    ->defaults(array(
        'controller' => 'Migrate',
        'action'     => 'update_user_project_notifications',
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