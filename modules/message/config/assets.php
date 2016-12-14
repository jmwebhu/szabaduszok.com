<?php

/**
 * AssetCollection initialize
 */
AssetCollection::instance()
    ->setCss('list', 'list.css')
    ->setJs('list', 'list.js')
    ->setJs('twig', ['twig.min.js', 'twig.config.js'])
;

/**
 * AssetManager initialize
 */
AssetManager::instance()
    ->addController('message_list')
        ->addAction('index')
            ->addCss(['list'])
            ->addJs(['twig' ,'list'])
;
