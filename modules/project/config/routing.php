<?php

Route::set('projectCreate', 'uj-szabaduszo-projekt')
    ->defaults([
        'controller'    => 'Project_Create',
        'action'        => 'index'
    ]);

Route::set('projectUpdate', 'szabaduszo-projekt-szerkesztes/<slug>')
    ->defaults([
        'controller'    => 'Project_Update',
        'action'        => 'index'
    ]);
    
Route::set('projectProfile', 'szabaduszo-projekt/<slug>')
    ->defaults([
        'controller'    => 'Project_Profile',
        'action'        => 'index'
    ]);
    
Route::set('projectList', 'szabaduszo-projektek(/<page>)')
    ->defaults([
    	'controller'    => 'Project_List',
   		'action'        => 'index'
    ]);

Route::set('projectAjax', 'project/ajax/<actiontarget>(/<maintarget>)')
    ->defaults([
        'controller'    => 'Project_Ajax',
        'action'        => 'index'
    ]);
