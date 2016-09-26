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

        $data = [
            'user_id' => 1,
            'name'  => 'Teszt1',
            'short_description' => 'Rövid leírás1',
            'long_description' => 'Hosszú leírás1',
            'email' => 'joo1@jmweb.hu',
            'phonenumber'   => '06301111111',
            'search_text' => 'kereső szöveg1',
            'expiration_date' => '2016-11-12',
            'salary_high' => 1500,
            'salary_low' => 3000
        ];

        $project1 = new Entity_Project();
        $project1->submit($data);
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
