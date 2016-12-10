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

    /**
     * @param int $index
     * @param array $array
     * @return bool
     */
    public static function isLastIndex($index, array $array)
    {
        $count = (count($array) == 0) ? 0 : count($array) - 1;
        return $index == $count;
    }

    /**
     * @param array
     * @param string|int
     * @param mixed
     * @return array
     */
    public static function setKey(array $array, $key, $value = [])
    {
        $result = $array;
        if (!isset($result[$key])) {
            $result[$key] = $value;
        }

        return $result;
    }
    
}
