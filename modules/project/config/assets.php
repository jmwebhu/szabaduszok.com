<?php

/**
 * AssetCollection initialize
 */
AssetCollection::instance()
    ->setJs('create', 'sites/create.js')
    ->setJs('list', 'sites/list.js')
    ->setJs('profile', 'sites/profile.js')

    ->setJs('project-partner', 'sites/project-partner.js', ['js' => ['fancybox']])
    
    ->setJs('wysihtml5', 'plugins/bootstrap3-wysihtml5.all.min.js')
    
    ->setCss('wysihtml5', 'plugins/bootstrap3-wysihtml5.min.css')
;

/**
 * AssetManager initialize
 */
AssetManager::instance()
    ->addController('project_create')
        ->addAction('index')
            ->addJs(['wysihtml5', 'create'])
            ->addCss(['wysihtml5'])

    ->addController('project_update')
        ->addAction('index')
            ->addJs(['wysihtml5', 'create'])
            ->addCss(['wysihtml5'])

    ->addController('project_list')
        ->addAction('index')
            ->addJs(['list'])

    ->addController('project_profile')
        ->addAction('index')
            ->addJs(['barrating', 'profile', 'project-partner'])
            ->addCss(['font-awesome-stars'])
;
