<?php

class EntityTest extends Unittest_TestCase
{
    public function testMapModelToThis()
    {
        $projectModel = new Model_Project();
        $projectModel->project_id = 1;
        $projectModel->name = 'Teszt';
    }
}