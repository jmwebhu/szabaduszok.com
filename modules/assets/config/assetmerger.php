<?php defined('SYSPATH') OR die('No direct script access.');

$modules = Kohana::modules();
$app = realpath(APPPATH.'..'.DIRECTORY_SEPARATOR."media").DIRECTORY_SEPARATOR;
$directories = array(
    'js'=>array(
        $app."js".DIRECTORY_SEPARATOR,
    ),
    'css'=>array(
        $app."css".DIRECTORY_SEPARATOR,
    )
);

foreach($modules as $item)
{
    if (is_dir($item."media")) {
        $currentBase = $item."media".DIRECTORY_SEPARATOR;
        $directories['js'][] = $currentBase."js".DIRECTORY_SEPARATOR;
        $directories['css'][] = $currentBase."css".DIRECTORY_SEPARATOR;
    }
}

return array(
	'merge'      		=> [Kohana::PRODUCTION, Kohana::STAGING],
	'folder'     		=> 'web',
    'compressFolder'	=> 'compressed',
	'load_paths' 		=> [
		Assets::JAVASCRIPT => $directories['js'],
		Assets::STYLESHEET => $directories['css'],
	],
	'processor'  		=> [
	    Assets::STYLESHEET => 'cssmin',
        Assets::JAVASCRIPT => 'jsminplus',
	],
	'docroot' 			=> DOCROOT,
    'webroot' 			=> URL::base(null,null),
    'rebuildTime' 		=> 24 * 60 * 60,
    'forceBuild' 		=> Kohana::PRODUCTION,

    'publishFolders'	=> ['pic','fonts','images','img'],
    'onlyInclude'		=> (Kohana::$environment == Kohana::DEVELOPMENT),
);
