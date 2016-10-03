<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {

        $entity = new Entity_Project(89);
        $business = $entity->getBusiness();

        echo Debug::vars($entity->getNameCutOffAt());
        echo Debug::vars($business->getNameCutOffAt());
        exit;
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
