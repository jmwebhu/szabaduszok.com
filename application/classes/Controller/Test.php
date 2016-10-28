<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        //$event = Model_Event_Factory::createEvent(Model_Event::TYPE_PROJECT_NEW);
        $event = new Model_Event_Project_New(1);
        $user = new Model_User_Freelancer();
        //echo Debug::vars($user);

        echo Debug::vars($event->object_name());
        echo Debug::vars($user->object_name());
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
