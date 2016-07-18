<?php
/**
 * User: MyCroft
 * Date: 2013.07.18.
 * Time: 12:05
 * Project: d2cadmin3.3
 * Company: d2c
 */
class Kohana_Asset
{
    public $source;
    public $sourcePath;
    public $type;
    public $condition;
    public $remote = false;

    public static function factory($source,$sourcePath,$type,$conditon)
    {
        $asset = new Asset();
        $asset->source = $source;
        $asset->sourcePath = $sourcePath;
        $asset->type = $type;
        $asset->condition = $conditon;
        if (strpos($source,'http://') || strpos($source,'https://')) $asset->remote = true;

        return $asset;
    }

    public function compress($processor)
    {
        $class = 'Asset_Processor_'.ucfirst($processor);
        return $class::compress($this);
    }
}