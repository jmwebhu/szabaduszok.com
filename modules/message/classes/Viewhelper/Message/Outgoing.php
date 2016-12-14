<?php

class Viewhelper_Message_Outgoing extends Viewhelper_Message
{
    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_OUTGOING;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return self::COLOR_OUTGOING;
    }
}