<?php
/**
 * User: MyCroft
 * Date: 2013.07.18.
 * Time: 11:44
 * Project: d2cadmin3.3
 * Company: d2c
 */
class Kohana_Assets
{
    /**
     * Call this method to get singleton
     *
     * @return AssetCollection
     */
    private $_config = array();
    private $_assetics = array();
    private $_links = array();
    private $_controller = "";
    private $_action = "";

    const JAVASCRIPT = 'js';
    const STYLESHEET = 'css';

    public static function instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Kohana_Assets();
            $inst->_config = Kohana::$config->load('assetmerger');
        }
        return $inst;
    }

    /**
     * Private ctor so nobody else can instance it
     *
     */
    private function __construct() { }

    private function add($source, $type, $condition = false)
    {
        if (!$this->isRemote($source))
        {
            $sourcePath = $this->getSourcePath($source,$type);
            if (is_file($sourcePath))
                if ($this->_config->onlyInclude === false) {
                    $this->_assetics[] = Asset::factory($source,$sourcePath,$type,$condition);
                } else {
                    $base = realpath(str_replace("application","",APPPATH)) . DIRECTORY_SEPARATOR;
                    $remotebase = Kohana::$config->load('basesettings.REMOTEPATH');

                    if (strpos($sourcePath,$base) !== false) {
                        $includeFile = str_replace(DIRECTORY_SEPARATOR,"/",trim(str_replace($base,"",$sourcePath),DIRECTORY_SEPARATOR));
                    } else {
                        $includeFile = str_replace(DIRECTORY_SEPARATOR,"/",trim(str_replace($remotebase,"",$sourcePath),DIRECTORY_SEPARATOR));
                        $includeFile = Kohana::$config->load('basesettings.REMOTEURL').'/'.$includeFile;
                    }



                    $this->setLink($includeFile,$type, true);
                }
            else {
                throw new Exception($source." not found in :".join(', ',$this->_config->load_paths[$type]));
            }
        } else {
            $this->setLink($source,$type, true);
        }

        return $this;
    }

    public function controller($controller)
    {
        $this->_controller = $controller;
        return $this;
    }

    public function action($action)
    {
        $this->_action = $action;
        return $this;
    }

    public function css($source, $condition = false)
    {
        return $this->add($source, Assets::STYLESHEET,$condition);
    }

    public function js($source, $condition = false)
    {
        return $this->add($source, Assets::JAVASCRIPT,$condition);
    }

    public function render()
    {
        $mergeSource = array('css'=>'','js'=>'');

        if ($this->enabledMerge() and $this->_config->onlyInclude === false)
        {
            $mustProcess = $this->mustProcessFile();

            if ($mustProcess['js']['mustProcess'] or $mustProcess['css']['mustProcess'])
            {
                foreach($this->_assetics as $item)
                {
                    if ($mustProcess[$item->type]['mustProcess']) {

                    if ($this->doCompress($item->source)) {
                        $compress = $item->compress($this->_config->processor[$item->type]);
                    } else {
                        $compress = file_get_contents($item->sourcePath);
                    }

                    if ($item->type == Assets::STYLESHEET) $compress = self::rewriteAllUrl($item,$compress);
                        $mergeSource[$item->type].= "/* ".$item->source." */\n".rtrim($compress,';');
                        $mergeSource[$item->type].= ($item->type == 'js') ? ";\n\n" : "\n\n";
                    }

                }

                foreach($mergeSource as $key=>$item)
                {
                    if ($mustProcess[$key]['mustProcess'])
                    {
                        $filename = $this->_config['compressFolder']."/".$this->_controller."/".$this->_action."/".'compressed.'.$key;
                        $this->writeFile($filename,$key,$item);
                        $this->setLink($filename,$key);
                    } else {
                        $this->setLink($mustProcess[$key]['include'],$key);
                    }

                }

            } else {
                $this->setLink($mustProcess['js']['include'],'js');
                $this->setLink($mustProcess['css']['include'],'css');
            }

        } else {
            foreach($this->_assetics as $item)
            {
                $this->setLink($item->source,$item->type);
            }
        }

        return array(
            'css'=>join("\n",$this->_links['css']),
            'js'=>join("\n",$this->_links['js']),
        );
    }

    public function __toString()
    {
        return $this->render();
    }

    private function getNewFilePath($source, $type)
    {
        $folder = $this->_config->docroot.$this->_config->folder.DIRECTORY_SEPARATOR;
        $newFile = $folder.$type.DIRECTORY_SEPARATOR.str_replace('/',DIRECTORY_SEPARATOR,$source);

        return $newFile;
    }

    private function doCompress($source)
    {
        return (strpos($source,'.min.') === false);
    }

    private function isRemote($source)
    {
        $isRemote = false;
        if (strpos($source,'http') === false) {
            if (strpos($source,'https') !== false) {
                $isRemote = true;
            }
        } else $isRemote = true;

        return $isRemote;
    }

    private function setLink($source, $type, $remote = false)
    {

        $tag = ($type == 'css') ? 'style' : 'script';
        $url = (!$remote) ? $this->_config->folder."/".$type."/".$source : $source;

        $this->_links[$type][] =  HTML::$tag($url);
    }

    private function writeFile($source, $type, $content)
    {
        $folder = $this->_config->docroot.$this->_config->folder.DIRECTORY_SEPARATOR;
        $newFile = $this->getNewFilePath($source,$type);

        $this->createDirs($folder,$source,$type);
        $putContent = file_put_contents($newFile,$content);

        return $putContent;
    }

    private function mustProcessFile()
    {
        return array(
            'js'=>array(
                'mustProcess'=>$this->checkFileWrite(realpath(APPPATH.'..'.DIRECTORY_SEPARATOR.$this->_config['folder'].DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.$this->_config['compressFolder'].DIRECTORY_SEPARATOR.$this->_controller.DIRECTORY_SEPARATOR.$this->_action.DIRECTORY_SEPARATOR.'compressed.js')),
                'include'=>$this->_config['compressFolder'].'/'.$this->_controller.'/'.$this->_action.'/'.'compressed.js',
            ),
            'css'=>array(
                'mustProcess'=>$this->checkFileWrite(realpath(APPPATH.'..'.DIRECTORY_SEPARATOR.$this->_config['folder'].DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$this->_config['compressFolder'].DIRECTORY_SEPARATOR.$this->_controller.DIRECTORY_SEPARATOR.$this->_action.DIRECTORY_SEPARATOR.'compressed.css')),
                'include'=>$this->_config['compressFolder'].'/'.$this->_controller.'/'.$this->_action.'/'.'compressed.css',
            )
        );
    }

    private function checkFileWrite($file)
    {
        $write = false;
        if (Kohana::$environment == $this->_config->forceBuild) $write = true;
        else if (!file_exists($file)) $write = true;
        else if (time() >= filemtime($file) + $this->_config->rebuildTime) $write = true;

        return $write;
    }

    private function createDirs($root,$source,$type)
    {
        $typeRoot = $root.$type;
        if (!is_dir($typeRoot)) mkdir($typeRoot,0777);

        $sourceParts = explode('/',$source);
        if (count($sourceParts) > 1) {
            unset($sourceParts[count($sourceParts)-1]);
            $urlTree = "";
            foreach($sourceParts as $item)
            {
                $urlTree.= $item;
                if (!is_dir($typeRoot.DIRECTORY_SEPARATOR.$urlTree)) mkdir($typeRoot.DIRECTORY_SEPARATOR.$urlTree,0777);
                $urlTree.= DIRECTORY_SEPARATOR;
            }
        }
    }

    private function getSourcePath($source,$type)
    {
        foreach($this->_config->load_paths[$type] as $item)
        {
            if (is_file(realpath($item.$source))) {
                return realpath($item.$source);
            }
        }

        return false;
    }

    private function enabledMerge()
    {
        $merge = false;
        if (is_array($this->_config->merge) and in_array(Kohana::$environment,$this->_config->merge)) $merge = true;
        else if ($this->_config->merge == Kohana::$environment) $merge = true;

        return $merge;
    }

    private function rewriteAllUrl(Asset $asset,$compress)
    {
        $config = $this->_config;
        $urls = self::getUrls($compress);

        $rwBase = $config->webroot.$config->folder."/";
        $replaceUrl = array();
        
        foreach($urls as &$item)
        {           	
        	//echo Debug::vars($item);
        	$replacedTmp = str_replace('"', '', $item);
        	$replaced = str_replace("'", '', $replacedTmp);
        	
        	$full = $rwBase . trim(str_replace('../','',$replaced),"'");
        	
            $replaceUrl[] = str_replace('"', '', $full);
                       
            $item = trim($item,"'");
            
            //echo Debug::vars($replaceUrl[count($replaceUrl) - 1]);
        }
        
        

        $compress = str_replace($urls,$replaceUrl,$compress);

        return $compress;
    }

    private function getUrls($compress)
    {
        $re = '/url\(([^\)]+)/i';
        preg_match_all($re, $compress, $url);
        $result = array();
        foreach($url[1] as $item)
        {
            if (strpos($item,'data:') === false) $result[] = $item;
        }

        return $result;
    }
}