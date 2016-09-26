<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {	        
        $project = new Entity_Project();
        $project->setUserId(1);
        $project->setName('Teszt');
        $project->setShortDescription('rövid leírás');
        $project->setLongDescription('Hosszú leírás');
        $project->setEmail('joomartin@jmweb.hu');
        $project->setPhonenumber('06301923380');
        $project->setIsActive(1);
        $project->setIsPaid(1);
        $project->setSearchText('Kereső szöveg');
        $project->setExpirationDate('2016-10-12');
        $project->setSalaryType(1);
        $project->setSalaryLow(1000);
        $project->setSalaryHigh(2500);

        $project->save();
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
