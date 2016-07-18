<?php

Route::set('projectCreate', 'uj-szabaduszo-projekt')
    ->defaults([
        'controller'    => 'Project',
        'action'        => 'create'
    ]);

Route::set('projectUpdate', 'szabaduszo-projekt-szerkesztes/<slug>')
    ->defaults([
        'controller'    => 'Project',
        'action'        => 'update'
    ]);
    
Route::set('projectProfile', 'szabaduszo-projekt/<slug>')
    ->defaults([
        'controller'    => 'Project',
        'action'        => 'profile'
    ]);
    
Route::set('projectList', 'szabaduszo-projektek(/<page>)')
    ->defaults([
    	'controller'    => 'Project',
   		'action'        => 'list'
    ]);

Route::set('projectAjax', 'project/ajax/<actiontarget>(/<maintarget>)')
    ->defaults([
        'controller'    => 'Project',
        'action'        => 'ajax'
    ]);
