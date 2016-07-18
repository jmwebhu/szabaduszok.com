<?php defined('SYSPATH') or die('No direct script access.');

class Controller_DefaultTemplate extends Controller_Twig
{
	protected $_benchmark = null;
	
    public function before()
    {
    	//$user = $this->context->loggedUser = Auth::instance()->get_user(); 
    	
    	// Be sure to only profile if it's enabled
    	if (Kohana::$profiling === TRUE)
    	{
    		// Start a new benchmark
    		$this->_benchmark = Profiler::start('default', __FUNCTION__);
    	}
    	
        $this->context->ROOT 		= URL::base(null, true);
        $this->context->title 		= 'A magyar freelancer platform';
        $this->context->env		    = Kohana::$environment;

        $this->context->PRODUCTION  = 10;
        $this->context->STAGING     = 20;
        $this->context->TESTING     = 30;
        $this->context->DEVELOPMENT = 40;
    }

    public function after()
    {
        if ($this->auto_render)
        {        	
            $assets =  AssetManager::instance()->getRequestAssets();
            $files = AssetCollection::instance()->getAssets($assets['css'], $assets['js']);

            $assets = Assets::instance()
                ->controller($this->request->controller())
                ->action($this->request->action());

            foreach($files['css'] as $css)
            {
                $assets->css($css);
            }

            foreach($files['js'] as $js)
            {
                $assets->js($js);
            }                        
                        
            $this->context->assets = $assets->render();  
            $this->context->loggedUser = Auth::instance()->get_user();
        }
        
        if ($this->request->is_ajax())
        {
        	$this->auto_render = false;
        	$this->response->headers('Content-Type', 'application/json');
        }
        

        if ($this->_benchmark)
        {
        	// Stop the benchmark
        	Profiler::stop($this->_benchmark);
        	$this->context->profiler = View::factory('profiler/stats');
        }                

        parent::after();
    }
    
    /**
     * Alapertelmezett atiranyitas. Ha valamelyik action -ben hiba van, pl 404, vagy 403, ezt hivja meg
     * 
     * @param Exception $ex		action altal dobott kivetel
     */
    public function defaultExceptionRedirect(Exception $ex)
    {
    	$user = Auth::instance()->get_user();
    	
    	/*
    	 * Nincs belepett felhasznalo. Ilyenkor belepesre iranyitjuk, es Session -be eltesszuk a jelenlegi url -t,
    	 * belepes utan pedig vissza iranyitjuk ide. Ez a redirect_url
    	 */
    	if (!$user->loaded())
    	{
    		$url = Route::url('login');    
    		
    		// Csak Forbidden hiba eseten
    		if ($ex instanceof HTTP_Exception_403)
    		{
    			/**
    			 * @var $ex HTTP_Exception_403	A kiveletben benne van az URL, ami dobta
    			 */
    			$route = $ex->getRedirectRoute();    			
    			
    			// Ha be van allitva az url -hez tartozo route
    			if ($route)
    			{
    				Session::instance()->set('redirect_url', Route::url(Route::name($route), ['slug' => $ex->getRedirectSlug()]));
    			}
    		}
    		
    		Session::instance()->set('error', 'Az oldal megtekintéséhez kérjük lépj be');
    	}
    	else		// Belepett felhasznalo, olyan oldalt akart megnezni, amihez nincsen jogosultsaga. Profilra iranyitunk, altalanos hiba uzenettel
    	{
    		Session::instance()->set('error', $ex->getMessage());
    	
    		// Szabaduszo, vagy pt profil
    		$url = ($user->type == 1) ? Route::url('freelancerProfile', ['slug' => $user->slug]) : Route::url('projectOwnerProfile', ['slug' => $user->slug]);
    	}
    	
    	// Atiranyitas
    	header('Location: ' . $url, true, 302);
    	die();
    }
}
