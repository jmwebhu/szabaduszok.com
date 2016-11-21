<?php
class View_Extensions extends Twig_Extension {

    public function getTokenParsers() {
        return array(
            new ViewExtensionHtmlTokenParser(), // all HTML methods
            new ViewExtensionFormTokenParser(), // all Form methods
            new ViewExtensionDebugTokenParser(), // all Debug methods
        );
    }

    public function getFilters() {
        return array(
            'limit_words'       =>  new Twig_Filter_Function('Text::limit_words'),
            'i18n'              =>  new Twig_Filter_Function('Twigextension::i18n'),
            'config'            =>  new Twig_Filter_Function('Twigextension::config'),
            'pad'               =>  new Twig_Filter_Function('Twigextension::pad'),            
            'mb_strlen'         =>  new Twig_Filter_Function('Twigextension::mb_strlen'),
            'mb_substr'         =>  new Twig_Filter_Function('Twigextension::mb_substr'),
            'shortest'          =>  new Twig_Filter_Function('Twigextension::shortest'),
            'shortest_word'     =>  new Twig_Filter_Function('Twigextension::shortest_word'),
            'find_all'          =>  new Twig_Filter_Function('Twigextension::find_all'),
            'fuzzy_span'        =>  new Twig_Filter_Function('Twigextension::fuzzy_span'),
        );
    }
    
    public function getFunctions() {
        return array(
            'path'              =>  new Twig_Function_Function('Twigextension::path'),
            'url'               =>  new Twig_Function_Function('Twigextension::url'),
            'getImage'          =>  new Twig_Function_Function('Twigextension::getImage'),
            'getUserfile'       =>  new Twig_Function_Function('Twigextension::getUserfile'),
            'logged_in'         =>  new Twig_Function_Function('Twigextension::logged_in'),
            'get_user'          =>  new Twig_Function_Function('Twigextension::get_user'),
            'isGranted'         =>  new Twig_Function_Function('Twigextension::isGranted'),
            'hasRole'           =>  new Twig_Function_Function('Twigextension::hasRole'),
			'in_array'          =>  new Twig_Function_Function('Twigextension::in_array')
        );
    }

    public function getName() {
        return 'view_extension';
    }
}