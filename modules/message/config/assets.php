<?php

/**
 * AssetCollection initialize
 */
AssetCollection::instance()
    ->setCss('list', 'list.css')
;

/**
 * AssetManager initialize
 */
AssetManager::instance()
    ->addController('message_list')
        ->addAction('index')
            ->addCss(['list'])
;
