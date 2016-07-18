<?php
/**
 * User: Tibor
 * Date: 2013.07.18.
 * Time: 21:49
 * Project: d2cadmin3.3
 * Company: d2c
 */
class Kohana_Asset_Processor_Jsminplus
{
    public static function compress(Asset $asset)
    {
        $content = file_get_contents($asset->sourcePath);
        $minifiedJs = JSMinPlus::minify($content);

        return $minifiedJs;
    }
}