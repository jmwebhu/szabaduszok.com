<?php

class Transaction_Message_Select_Factory
{
    /**
     * @return Transaction_Message_Select
     */
    public static function createSelect()
    {
        return new Transaction_Message_Select(new Model_Message());
    }
}