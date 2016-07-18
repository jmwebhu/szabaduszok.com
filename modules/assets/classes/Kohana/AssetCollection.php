<?php
/**
 * User: MyCroft
 * Date: 2013.05.03.
 * Time: 13:00
 * To change this template use File | Settings | File Templates.
 */

class Kohana_AssetCollection {

    protected $_collections = array();

    /**
     * Call this method to get singleton
     *
     * @return AssetCollection
     */
    public static function instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new AssetCollection();
        }
        return $inst;
    }

    /**
     * Private ctor so nobody else can instance it
     *
     */
    private function __construct() { }

    function setCss($tag,$files,array $dependences = array())
    {
        if (empty($tag) || empty($files)) {
            return false;
        }
        if (!is_array($files)) $files = array($files);

        return $this->addElement('css',$tag,$files,$dependences);
    }

    function setJs($tag,$files,array $dependences = array())
    {
        if (empty($tag) || empty($files)) {
            return false;
        }
        if (!is_array($files)) $files = array($files);
        
        return $this->addElement('js',$tag,$files,$dependences);
    }

    private function addElement($type,$tag,$files,$dependences)
    {
        $this->_collections[$type][$tag] = array('files'=>$files,'dependences'=>$dependences);
        return $this;
    }

    function getAssets(array $css,array $js)
    {
        $result = array('css'=>array(),'js'=>array());

        foreach($css as $item)
        {
            $temp = $this->getCss($item);
            if (isset($temp['css'])) $result['css'] = Arr::merge($result['css'],$temp['css']);
            if (isset($temp['js'])) $result['js'] = Arr::merge($result['js'],$temp['js']);
        }
        foreach($js as $item)
        {
            $parts = explode("?",$item);
            $temp = $this->getJs($parts[0]);

            if (isset($temp['css'])) $result['css'] = Arr::merge($result['css'],$temp['css']);
            if (isset($temp['js'])) {
                foreach($temp['js'] as $index=>$item)
                {
                    if (isset($parts[1])) $temp['js'][$index] = str_replace("::param::",$parts[1],$temp['js'][$index]);
                    $temp['js'][$index] = str_replace("::lang::",I18n::$lang,$temp['js'][$index]);
                }

                $result['js'] = Arr::merge($result['js'],$temp['js']);
            }
        }

        return $result;
    }

    function getCss($tag)
    {
        if (!isset($this->_collections['css'][$tag]))
           return false;

        return $this->allDependences($tag,'css');
    }

    function getJs($tag)
    {
        if (!isset($this->_collections['js'][$tag]))
            return false;

        return $this->allDependences($tag,'js');
    }

    private function allDependences($tag,$type)
    {
        $deps = array();
        return self::getDependences($deps,$tag,$type);
    }

    private function getDependences(&$array,$tag,$type)
    {
        $deps = $this->_collections[$type][$tag];
        $inversType = ($type == 'css') ? 'js' : 'css';
        if (isset($deps['dependences'][$type]) and is_array($deps['dependences'][$type])) {
            foreach($deps['dependences'][$type] as $depsItem)
            {
                self::getDependences($array,$depsItem,$type);
            }
        }
        if (isset($deps['dependences'][$inversType]) and is_array($deps['dependences'][$inversType])) {
            foreach($deps['dependences'][$inversType] as $depsItem)
            {
                self::getDependences($array,$depsItem,$inversType);
            }
        }

        foreach($deps['files'] as $depsFiles)
        {
            $array[$type][] = $depsFiles;
        }

        return $array;
    }
}