<?php

Route::set('messagesList', 'uzenetek(/<slug>)')
    ->defaults([
        'controller'    => 'Message_List',
        'action'        => 'index'
    ]);

Route::set('messagesContact', 'kapcsolatfelvetel/<userslug>')
    ->defaults([
        'controller'    => 'Message',
        'action'        => 'contact'
    ]);