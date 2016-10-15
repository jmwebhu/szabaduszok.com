<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $user = new Entity_User_Freelancer(399);
        $mailinglist = Gateway_Mailinglist_Factory::createMailinglist($user);

        echo Debug::vars($mailinglist);
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
