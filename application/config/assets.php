<?php

/**
 * AssetCollection initialize
 */
AssetCollection::instance()
    ->setJs('jquery', 'jquery-2.2.2.min.js')
    ->setJs('jquery3', 'jquery-3.0.0.min.js')

    ->setJs('blocs', 'blocs.min.js')
    ->setJs('bootstrap', 'bootstrap.min.js')
    
    ->setJs('default', 'default.js')
    ->setJs('validator', 'validator.js')      
    ->setJs('tabs', 'tabs.js')
    ->setJs('select2', 'select2.full.min.js')
    ->setJs('isloading', 'jquery.isloading.min.js')
    
    ->setJs('ajax-builder', 'ajaxBuilder.js')   
    ->setJs('jquery-confirm', 'jquery-confirm.js')    
    ->setJs('fancybox', 'jquery.fancybox.pack.js')

    ->setCss('isloading', 'isloading.css')      
    ->setCss('font-awesome', 'font-awesome.min.css')
    ->setCss('ionicons', 'ionicons.min.css')   
   	->setCss('select2', 'select2.min.css') 
	->setCss('select2-bootstrap', 'select2-bootstrap.min.css')	
	->setCss('jquery-confirm', 'jquery-confirm.css')
    ->setCss('fancybox', 'jquery.fancybox.css')

    ;

$defaultJs = ['jquery', 'blocs', 'bootstrap', 'validator', 'isloading', 'default', 'tabs', 'select2', 'ajax-builder', 'jquery-confirm'];
$defaultCss = ['font-awesome', 'ionicons', 'select2', 'select2-bootstrap', 'isloading', 'jquery-confirm'];

AssetManager::instance()
    ->defaultCss($defaultCss)
    ->defaultJs($defaultJs)
    		
    ->addController('main')
        ->addAction('index')
            ->addJs(['fancybox', 'landing'])
            ->addCss(['fancybox', 'landing'])		
    		
;    		