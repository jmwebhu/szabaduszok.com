<?php

Route::set('messagesList', 'uzenetek(/<slug>)')
    ->defaults([
        'controller'    => 'Message_List',
        'action'        => 'index'
    ]);