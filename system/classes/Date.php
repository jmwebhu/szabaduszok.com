<?php defined('SYSPATH') OR die('No direct script access.');

class Date extends Kohana_Date 
{
    /**
     * @var int
     */
    protected static $_textifyMaxInterval = 6;

    /**
     * @param  string
     * @return string
     */
    public static function textifyDay($date)
    {
        $differenceDays = self::differnce($date);
        $day            = $date;

        if ($differenceDays == 0) {
            $day = 'Today';
        } elseif ($differenceDays <= self::$_textifyMaxInterval) {
            $dateTime   = new DateTime($date);
            $day        = $dateTime->format('l');
        }

        return $day;
    }

    /**
     * @param  string
     * @param  string
     * @return int
     */
    public static function differnce($date, $otherDate = null, $format = 'd')
    {
        $realOtherDate  = ($otherDate) ? $otherDate : date('Y-m-d', time());

        $givenDateTime  = new DateTime($date);
        $nowDateTime    = new DateTime($realOtherDate);

        $differenceInterval = $nowDateTime->diff($givenDateTime);        
        
        return $differenceInterval->{$format};
    }
    
}
