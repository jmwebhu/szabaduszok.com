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
    public static function isLastIndex($index, array $array, $negativeModifier = 0)
    {
        $count = (count($array) == 0) ? 0 : count($array) - 1;
        if ($negativeModifier) {
            $count -= $negativeModifier;
        }
        
        return $index == $count;
    }

    /**
     * @param array
     * @param mixed
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

    /**
     * @param  array  $array
     * @param  string $separator
     * @return string
     */
    public static function concatValues(array $array, $separator = ',')
    {
        $concatValue = '';
        foreach ($array as $i => $value) {
           if (is_scalar($value)) {
                $concatValue    .= self::getAppendedValueByIndex($array, $i, $value, $separator);
           }
       }   

       return $concatValue;
    }

    /**
     * @param  array  $array
     * @param  mixed $index
     * @param  mixed $value
     * @param  mixed $append
     * @return mixed
     */
    public static function getAppendedValueByIndex(array $array, $index, $value, $append)
    {
        $result = $value;
        if (!self::isLastIndex($index, $array)) {
            $result = $value . $append;
        }

        return $result;
    }
    
    /**
     * @param  array  $array
     * @return mixed
     */
    public static function last(array $array)
    {
        return Arr::get($array, count($array) - 1, null);
    }
}
