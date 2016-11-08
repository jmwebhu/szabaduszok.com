<?php defined('SYSPATH') OR die('No direct script access.');

class Arr extends Kohana_Arr
{
    /**
     * @param array $array
     * @return array
     */
    public static function uniqueString(array $array)
    {
        $temp = $array;
        for ($i = 0; $i < count($array) - 1; $i++) {
            if (Text::compareStringsUtf8SafeCaseInsensitive($array[$i], $array[$i + 1]) == 0) {
                unset($temp[$i + 1]);
            }
        }

        return array_values($temp);
    }
}
