<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $searchedIds = [1, 3];
        $projectSkillIds = [3, 4, 5, 1];

        $diff = array_diff($searchedIds, $projectSkillIds);
        echo Debug::vars('dif: ', $diff);

        /*$project1 = new Model_Project();
        $project1->project_id = 1;

        $project2 = new Model_Project();
        $project2->project_id = 2;

        $project3 = new Model_Project();
        $project3->project_id = 3;

        $project4 = new Model_Project();
        $project4->project_id = 4;

        $project5 = new Model_Project();
        $project5->project_id = 5;

        $data = [
            'skills' => [1, 2, 3, 4, 5, 6, 7],
            'complex' => true
        ];

        $search = Project_Search_Factory::getAndSetSearch($data);
        $search->setProjects([$project1, $project2, $project3, $project4, $project5]);
        //$res = $search->search();
        //echo Debug::vars($res);

        $reflection 	= new \ReflectionClass(get_class($search));
        $method 		= $reflection->getMethod('searchRelationsInProjects');

        $method->setAccessible(true);

        $method->invokeArgs($search, [new Model_Project_Skill()]);

        $res = $search->getMatchedProjects();*/
        //echo Debug::vars($res);
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
