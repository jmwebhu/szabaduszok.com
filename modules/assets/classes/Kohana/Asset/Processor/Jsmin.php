<?php
/**
 * User: Tibor
 * Date: 2013.07.18.
 * Time: 21:33
 * Project: d2cadmin3.3
 * Company: d2c
 */
class Kohana_Asset_Processor_Jsmin
{
    public static function compress(Asset $asset)
    {
        $content = file_get_contents($asset->sourcePath);
        $minifiedJs = JSMin::minify($content);

        return $minifiedJs;
    }
}