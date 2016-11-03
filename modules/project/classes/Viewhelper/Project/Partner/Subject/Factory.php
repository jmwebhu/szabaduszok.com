<?php

class Viewhelper_Project_Partner_Subject_Factory
{
    /**
     * @param ORM $subjectOrm
     * @return Viewhelper_Project_Partner_Subject
     */
    public static function createSubject(ORM $subjectOrm)
    {
        $subject = null;
        if ($subjectOrm instanceof Model_User) {
            $subject = new Viewhelper_Project_Partner_Subject_User();
        } elseif ($subjectOrm instanceof Model_Project) {
            $subject = new Viewhelper_Project_Partner_Subject_Project();
        }

        Assert::notNull($subject);
        return $subject;
    }
}