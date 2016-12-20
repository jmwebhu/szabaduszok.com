<?php

class Viewhelper_Message_Incoming extends Viewhelper_Message
{
    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_INCOMING;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return self::COLOR_INCOMING;
    }
}