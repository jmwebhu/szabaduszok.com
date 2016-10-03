<?php

/**
 * AssetCollection initialize
 */
AssetCollection::instance()
    ->setJs('create', 'sites/create.js')
    ->setJs('list', 'sites/list.js')
    ->setJs('profile', 'sites/profile.js')
    
    ->setJs('wysihtml5', 'plugins/bootstrap3-wysihtml5.all.min.js')
    
    ->setCss('wysihtml5', 'plugins/bootstrap3-wysihtml5.min.css')
;

/**
 * AssetManager initialize
 */
AssetManager::instance()
    ->addController('project')
        
        ->addAction('update')
            ->addJs(['wysihtml5', 'create'])
            ->addCss(['wysihtml5'])

    ->addController('project_list')
        ->addAction('index')
            ->addJs(['list'])

    ->addController('project_create')
        ->addAction('index')
            ->addJs(['wysihtml5', 'create'])
            ->addCss(['wysihtml5'])

    ->addController('project_profile')
        ->addAction('index')
            ->addJs(['barrating', 'profile'])
            ->addCss(['font-awesome-stars'])
;
