<?php

Route::set('landingAjax', 'landing/ajax/<actiontarget>')
	->defaults(array(
		'controller' => 'Landing',
		'action'     => 'ajax',
	));