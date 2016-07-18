<?php
/**
 * User: MyCroft
 * Date: 2013.07.18.
 * Time: 13:19
 * Project: d2cadmin3.3
 * Company: d2c
 */
class Kohana_Asset_Processor_Cssmin
{
    public static function compress(Asset $asset)
    {
        $content = file_get_contents($asset->sourcePath);
        $compressor = new CSSmin();

        $output = $compressor->run($content);

        return $output;
    }
}