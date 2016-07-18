<?php

Route::set('sendProjectNotification', 'cron/szabaduszo-projekt-ertesito')
	->defaults(array(
		'controller' => 'Cron',
		'action'     => 'send_project_notification',
	));