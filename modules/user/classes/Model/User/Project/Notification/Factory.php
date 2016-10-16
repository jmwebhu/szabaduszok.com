<?php

class Model_User_Project_Notification_Factory
{
    /**
     * @param string $type
     * @return Model_Relation
     */
    public static function createNotification($type)
    {
        $class = 'Model_User_Project_Notification_' . ucfirst($type);
        return new $class();
    }
}