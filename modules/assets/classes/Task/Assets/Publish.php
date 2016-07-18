<?php
/**
 * User: MyCroft
 * Company: design2code.hu
 * Product: d2cadmin
 * Date: 2013.05.11.
 * Time: 17:52
 *
 */

class Task_Assets_Publish extends Minion_Task
{
    protected $_options = array(
        'modul' => 'all',
    );

    public function build_validation(Validation $validation) {
        return parent::build_validation($validation)
            ->rule('modul', 'not_empty');
    }

    protected function _execute(array $params)
    {
        $root = realpath(APPPATH.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR);
        $base = $root.DIRECTORY_SEPARATOR."web";
        $searchFolders = Kohana::$config->load('assetmerger.publishFolders');
        $modules = $this->getDirectorys($params['modul'],$root);

        foreach($modules as $item)
        {
            $this->copyFiles($base,$item,$searchFolders);
        }
    }

    protected function getDirectorys($current,$root)
    {
        $modules = array($root.DIRECTORY_SEPARATOR.'media');
        foreach(Kohana::modules() as $item)
        {
            $dir = $item."media";
            if (is_dir($dir))
            {
                $modules[] = $dir;
            }
        }

        return $modules;
    }

    protected function copyFiles($root,$directory,$searchFolder)
    {
        $objects = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory),
            RecursiveIteratorIterator::SELF_FIRST);

        $currentBase = $root;
        $isCopy = false;

        foreach($objects as $name => $object){
            if ($object->isDir()) {
                $folder = explode(DIRECTORY_SEPARATOR,$object->getPathname());
                $currentPatch = end($folder);
                
                //echo "Path: ".$object->getPathname()."\n";
                if ($currentPatch != '.' and $currentPatch != '..') {
                    if ($this->isChoose($object->getPathname(),$searchFolder))
                    {
                        $newFolder = $root.DIRECTORY_SEPARATOR.$this->getChooseFolderName($object->getPathname(),$searchFolder);
                        $currentBase = $newFolder;

                        if (!file_exists($newFolder)) {
                            mkdir($newFolder,0777,true);
                        }
                        $isCopy = true;
                    } else {
                        $isCopy = false;
                    }

                }

            } else if ($isCopy) {
                if ($this->isChoose($object->getPathname(),$searchFolder))
                {
                    $originalFile = $object->getPath().DIRECTORY_SEPARATOR.$object->getFilename();

                    $newFile = $root.DIRECTORY_SEPARATOR.$this->getChooseFolderName($object->getPathname(),$searchFolder);
                    echo "Add: ".$newFile."\n";
                    copy($originalFile,$newFile);
                }
            }
            /*if ($object->isFile() and in_array($object->getExtension(),array('php','twig','js')))
                $files[] = $object->getPath().DIRECTORY_SEPARATOR.$object->getFilename();*/
        }

    }

    protected function isChoose($path,$folders)
    {
        foreach($folders as $item)
        {
            if (strpos($path,$item) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function getChooseFolderName($path,$folders)
    {
        foreach($folders as $item)
        {
            $pos = strpos($path,$item);
            if ($pos !== false) {
                return substr($path,$pos,strlen($path)-1);
            }
        }

        return false;
    }
}