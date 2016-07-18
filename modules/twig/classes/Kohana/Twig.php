<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Twig loader.
 *
 * @package  Kohana-Twig
 * @author   John Heathco <jheathco@gmail.com>
 */
class Kohana_Twig {

	/**
	 * @var  object  Kohana_Twig instance
	 */
	public static $instance;

	/**
	 * @var  object  Kohana_Twig configuration (Kohana_Config object)
	 */
	public static $config;

	public static function instance()
	{
		if ( ! Kohana_Twig::$instance)
		{
			// Load Twig configuration
			Kohana_Twig::$config = Kohana::$config->load('twig');

			// Create the the loader
            $viewDirs = array(APPPATH.'views');
            foreach(Kohana::modules() as $module)
            {
                if (is_dir($module.'views'))  $viewDirs[] = $module.'views';    
            }
			$loader = new Twig_Loader_Filesystem($viewDirs);

			// Set up Twig
			Kohana_Twig::$instance = new Twig_Environment($loader, Kohana_Twig::$config->environment);
            Kohana_Twig::$instance->addExtension(new Twig_Extension_Debug());

			foreach (Kohana_Twig::$config->extensions as $extension)
			{
				// Load extensions
				Kohana_Twig::$instance->addExtension(new $extension);
			}
            
		}

		return Kohana_Twig::$instance;
	}

	final private function __construct()
	{
		// This is a static class
	}

} // End Kohana_Twig
