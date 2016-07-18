<?php

return array
(
	'environment' => array
	(
		'debug'               => TRUE,
		'trim_blocks'         => FALSE,
		'charset'             => 'utf-8',
		'base_template_class' => 'Twig_Template',
		'cache'               => APPPATH.'cache/twig',
		'auto_reload'         => TRUE,
		'strict_variables'    => FALSE,
		'autoescape'          => TRUE,
		'optimizations'       => -1,
	),
	'extensions' => array
	(
		'Twig_Extension_Escaper', // native twig escaper
        'Twig_Extension_Optimizer', // native twig optimizer
        'Twig_Extension_StringLoader',
        'View_Extensions'
	),
	'suffix'         => '.twig',
	'context_object' => TRUE,
);