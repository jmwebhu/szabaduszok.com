<?php defined('SYSPATH') OR die('No direct script access.');

class HTTP_Exception_403 extends Kohana_HTTP_Exception_403 
{		
	/**
	 * Az a route, ahova at kell iranyitani a usert
	 * @var Route $_reddirect_route
	 */
	protected $_reddirect_route = null;
	
	/**
	 * Az atiranyitando route -hoz tartozo slug
	 * @var string $_redirect_slug
	 */
	protected $_redirect_slug = null;
	
	public function getRedirectRoute() { return $this->_reddirect_route; }	
	public function setRedirectRoute(Route $route) { $this->_reddirect_route = $route; }
	
	public function getRedirectSlug() { return $this->_redirect_slug; }
	public function setRedirectSlug($slug) { $this->_redirect_slug = $slug; }
}
