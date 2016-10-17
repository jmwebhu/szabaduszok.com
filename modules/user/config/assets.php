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
    ->setJs('projectowner-registration', 'sites/projectowner-registration.js')
    
    // Profilok
    ->setJs('freelancer-profile', 'sites/freelancer-profile.js')
    ->setJs('projectowner-profile', 'sites/projectowner-profile.js')
    
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

    ->addController('user')
    
        ->addAction('login')
            ->addJs(['login'])                      
            
        ->addAction('freelancerregistration')
        	->addJs(['freelancer-registration'])
        	
        ->addAction('projectownerregistration')
        	->addJs(['projectowner-registration'])
        	
        ->addAction('freelancerprofile')
        	->addJs(['barrating', 'freelancer-profile'])
        	->addCss(['font-awesome-stars'])
        	
        ->addAction('freelancerprofileedit')
        	->addJs(['freelancer-registration'])
        	
        ->addAction('projectownerprofile')
        	->addJs(['barrating', 'projectowner-profile'])
        	->addCss(['font-awesome-stars'])
        	
        ->addAction('projectownerprofileedit')
        	->addJs(['projectowner-registration'])
        	
        ->addAction('freelancers')        	
        	->addJs(['barrating', 'freelancers'])
        	->addCss(['font-awesome-stars'])
;
