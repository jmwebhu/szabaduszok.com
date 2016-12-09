<?php

/**
 * AssetCollection initialize
 */
AssetCollection::instance()
    ->setCss('speech', 'speech.css')
    ->setCss('list', 'list.css')
;

/**
 * AssetManager initialize
 */
AssetManager::instance()
    ->addController('message_list')
        ->addAction('index')
            ->addCss(['speech', 'list'])
;
