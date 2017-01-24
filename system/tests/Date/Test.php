<?php

class Date_Test extends Unittest_TestCase
{
    /**
     * @covers Date::textifyDay
     * @dataProvider textifyDayDataProvider()
     */
    public function testTextifyDay($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
    }

    public function textifyDayDataProvider()
    {
        $now        = date('Y-m-d', time());
        $timestamps = [];
        $dates      = [];
        $days       = [];

        for ($i = 0; $i < 10; $i++) {
            $timestamp      = strtotime($now . '-' . $i . ' days');
            $timestamps[]   = $timestamp;
            $dateTime       = new DateTime(date('Y-m-d', $timestamp));
            $days[]         = $dateTime->format('l');
        }

        foreach ($timestamps as $timestamp) {
            $dates[] = date('Y-m-d', $timestamp);
        }

        return [
            ['Today', Date::textifyDay($dates[0])],
            ['Yesterday', Date::textifyDay($dates[1])],
            [$days[2], Date::textifyDay($dates[2])],
            [$days[3], Date::textifyDay($dates[3])],
            [$days[4], Date::textifyDay($dates[4])],
            [$days[5], Date::textifyDay($dates[5])],
            [$days[6], Date::textifyDay($dates[6])],
            [$dates[7], Date::textifyDay($dates[7])],
            [$dates[8], Date::textifyDay($dates[8])],
            [$dates[9], Date::textifyDay($dates[9])],
            ['', Date::textifyDay()]
        ];
    }
    
}