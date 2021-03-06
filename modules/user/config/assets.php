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
    ->setJs('registration-common', 'sites/registration-common.js')
    
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
            ->addJs(['freelancer-registration', 'registration-common'])

    ->addController('user_create_employer')
        ->addAction('index')
            ->addJs(['employer-registration', 'registration-common'])

    ->addController('user_update_freelancer')
        ->addAction('index')
            ->addJs(['freelancer-registration', 'registration-common'])

    ->addController('user_update_employer')
        ->addAction('index')
            ->addJs(['employer-registration', 'registration-common'])

    ->addController('user_profile_freelancer')
        ->addAction('index')
            ->addJs(['barrating', 'freelancer-profile'])
            ->addCss(['font-awesome-stars'])

    ->addController('user_profile_employer')
        ->addAction('index')
            ->addJs(['barrating', 'employer-profile'])
            ->addCss(['font-awesome-stars'])


    ->addController('user_auth')
        ->addAction('login')
            ->addJs(['login'])

    ->addController('user_freelancers')
        ->addAction('index')
        	->addJs(['barrating', 'freelancers'])
        	->addCss(['font-awesome-stars'])
;
