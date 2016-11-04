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
            $user = Auth::instance()->get_user();
            $this->context->loggedUser = Entity_User::createUser($user->type, $user);
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
    	if (!$user->loaded()) {
    		$url        = Route::url('login');
            $message    = 'Az oldal megtekintéséhez kérjük lépj be';
    		
    		// Csak Forbidden hiba eseten
    		if ($ex instanceof HTTP_Exception_403) {
    			/**
    			 * @var $ex HTTP_Exception_403	A kiveletben benne van az URL, ami dobta
    			 */
    			$route = $ex->getRedirectRoute();    			
    			
    			// Ha be van allitva az url -hez tartozo route
    			if ($route) {
    				Session::instance()->set('redirect_url', Route::url(Route::name($route), ['slug' => $ex->getRedirectSlug()]));
    			}

                $message = $ex->getMessage();
    		}

    		Session::instance()->set('error', $message);

    	} else {
    		Session::instance()->set('error', $ex->getMessage());
    		$url = ($user->type == 1) ? Route::url('freelancerProfile', ['slug' => $user->slug]) : Route::url('projectOwnerProfile', ['slug' => $user->slug]);
    	}

    	header('Location: ' . $url, true, 302);
    	die();
    }

    /**
     * @param bool $expression
     * @throws HTTP_Exception_404
     */
    protected function throwNotFoundExceptionIfNot($expression)
    {
        if (!$expression) {
            throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');
        }
    }

    /**
     * @param bool $expression
     * @param string $message
     * @throws HTTP_Exception_403
     */
    protected function throwForbiddenExceptionIfNot($expression, $message = 'Nincs jogosultságod az odal megtekintéséhez')
    {
        if (!$expression) {
            throw new HTTP_Exception_403($message);
        }
    }
}
