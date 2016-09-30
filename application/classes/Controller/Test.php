<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $entity = new Entity_Project(46);

        echo Debug::vars($entity->getNameCutOffAt());
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
