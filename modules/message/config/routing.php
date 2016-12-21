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

Route::set('conversationAjax', 'conversation/ajax/<actiontarget>(/<maintarget>)')
    ->defaults([
        'controller'    => 'Conversation_Ajax',
        'action'        => 'index'
    ]);

Route::set('messageAjax', 'message/ajax/<actiontarget>(/<maintarget>)')
    ->defaults([
        'controller'    => 'Message_Ajax',
        'action'        => 'index'
    ]);