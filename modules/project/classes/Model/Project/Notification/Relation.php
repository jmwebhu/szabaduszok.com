<?php

abstract class Model_Project_Notification_Relation extends ORM
{
    /**
     * @return string
     */
    abstract public function getUserProjectNotificationRelationName();
}