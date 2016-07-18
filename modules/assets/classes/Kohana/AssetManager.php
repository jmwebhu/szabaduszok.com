<?php
/**
 * User: MyCroft
 * Date: 2013.05.03.
 * Time: 12:59
 * To change this template use File | Settings | File Templates.
 */

//TODO AssetManager
class Kohana_AssetManager {

    protected $_loadings = array();
    private $_currentController = false;
    private $_currentAction = false;

    /**
     * Call this method to get singleton
     *
     * @return AssetManager
     */
    public static function instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new AssetManager();
        }
        $inst->_currentController = false;
        $inst->_currentAction = false;

        return $inst;
    }

    /**
     * Private ctor so nobody else can instance it
     *
     */
    private function __construct()
    { 
    }

    public function defaultCss($css)
    {
        if (!array_key_exists('defaults',$this->_loadings)) $this->_loadings['defaults'] = array();
        if (!array_key_exists('css',$this->_loadings['defaults'])) $this->_loadings['defaults']['css'] = array();
        if (!is_array($css)) $css = array($css);

        $this->_loadings['defaults']['css'] = Arr::merge($this->_loadings['defaults']['css'],$css);

        return $this;
    }

    public function defaultJs($js)
    {
        if (!array_key_exists('defaults',$this->_loadings)) $this->_loadings['defaults'] = array();
        if (!array_key_exists('js',$this->_loadings['defaults'])) $this->_loadings['defaults']['js'] = array();
        if (!is_array($js)) $js = array($js);

        $this->_loadings['defaults']['js'] = Arr::merge($this->_loadings['defaults']['js'],$js);

        return $this;
    }

    public function addController($name)
    {
        if (!array_key_exists($name,$this->_loadings)) $this->_loadings[$name] = array();
        $this->_currentController = $name;

        return $this;
    }

    public function addAction($name)
    {
        if ($this->_currentController == false)
            throw new Exception("Controller not initialize!");
        else {
            if (!array_key_exists($name,$this->_loadings[$this->_currentController])) $this->_loadings[$this->_currentController][$name] = array();
            $this->_currentAction = $name;
        }

        return $this;
    }

    public function addCss($css)
    {
        if ($this->_currentController == false || $this->_currentAction == false)
            throw new Exception("Controller or Action not initialize!");

        if (!array_key_exists('css',$this->_loadings[$this->_currentController][$this->_currentAction])) $this->_loadings[$this->_currentController][$this->_currentAction]['css'] = array();
        if (!is_array($css)) $css = array($css);

        $this->_loadings[$this->_currentController][$this->_currentAction]['css'] = Arr::merge($this->_loadings[$this->_currentController][$this->_currentAction]['css'],$css);

        return $this;
    }

    public function addJs($js)
    {
        if ($this->_currentController == false || $this->_currentAction == false)
            throw new Exception("Controller or Action not initialize!");

        if (!array_key_exists('js',$this->_loadings[$this->_currentController][$this->_currentAction])) $this->_loadings[$this->_currentController][$this->_currentAction]['js'] = array();
        if (!is_array($js)) $js = array($js);

        $this->_loadings[$this->_currentController][$this->_currentAction]['js'] = Arr::merge($this->_loadings[$this->_currentController][$this->_currentAction]['js'],$js);

        return $this;
    }

    public function getRequestAssets()
    {
        $request = Request::current();
        $controller = strtolower($request->controller());
        $action = strtolower($request->action());

        if (isset($this->_loadings[$controller]) and isset($this->_loadings[$controller][$action]))
        {
            $css = (isset($this->_loadings[$controller][$action]['css'])) ? Arr::merge($this->_loadings['defaults']['css'],$this->_loadings[$controller][$action]['css']) : $this->_loadings['defaults']['css'];
            $js = (isset($this->_loadings[$controller][$action]['js'])) ? Arr::merge($this->_loadings['defaults']['js'],$this->_loadings[$controller][$action]['js']) : $this->_loadings['defaults']['js'];

            return array('css'=>$css,'js'=>$js);
        } else {
            return array('css'=>$this->_loadings['defaults']['css'],'js'=>$this->_loadings['defaults']['js']);
        }
    }
}
