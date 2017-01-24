<?php

/**
 * AssetCollection initialize
 */
AssetCollection::instance()
    ->setCss('list', 'list.css')
    ->setJs('twig', ['twig.min.js', 'twig.config.js'])
    ->setJs('messageList', 'list.js')
;

/**
 * AssetManager initialize
 */
AssetManager::instance()
    ->addController('message_list')
        ->addAction('index')
            ->addCss(['list'])
            ->addJs(['twig', 'messageList'])
;
