<?php

/**
 * AssetCollection initialize
 */
AssetCollection::instance()
    ->setJs('login', 'sites/login.js')
    ->setJs('profile', 'sites/profile.js')
    ->setJs('registration', 'sites/registration.js')
    ->setJs('passwordreminder', 'sites/passwordreminder.js')
    
    // Regisztraciok
    ->setJs('freelancer-registration', 'sites/freelancer-registration.js')
    ->setJs('employer-registration', 'sites/employer-registration.js')
    
    // Profilok
    ->setJs('freelancer-profile', 'sites/freelancer-profile.js')
    ->setJs('employer-profile', 'sites/employer-profile.js')
    
    // Szabaduszo lista
    ->setJs('freelancers', 'sites/freelancers.js')
    
    // Pluginok    
    ->setJs('barrating', 'plugins/jquery.barrating.min.js')
    ->setCss('font-awesome-stars', 'plugins/fontawesome-stars.css')
;

/**
 * AssetManager initialize
 */
AssetManager::instance()

    ->addController('user_create_freelancer')
        ->addAction('index')
            ->addJs(['freelancer-registration'])

    ->addController('user_create_employer')
        ->addAction('index')
            ->addJs(['employer-registration'])

    ->addController('user_update_freelancer')
        ->addAction('index')
            ->addJs(['freelancer-registration'])

    ->addController('user_update_employer')
        ->addAction('index')
            ->addJs(['employer-registration'])

    ->addController('user_profile_freelancer')
        ->addAction('index')
            ->addJs(['barrating', 'freelancer-profile'])
            ->addCss(['font-awesome-stars'])


    ->addController('user')
        ->addAction('login')
            ->addJs(['login'])
        	
        ->addAction('projectownerprofile')
        	->addJs(['barrating', 'employer-profile'])
        	->addCss(['font-awesome-stars'])
        	
        ->addAction('freelancers')        	
        	->addJs(['barrating', 'freelancers'])
        	->addCss(['font-awesome-stars'])
;
