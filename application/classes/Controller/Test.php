<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $project = new Entity_Project();
        $data = [
            'user_id'   => 2042,
            'name'      => 'Projekt',
            'short_description' => 'Rövid leírás',
            'long_description' => 'Hosszú leírás',
            'email' => 'martinasdf@szabaduszok.com',
            'phonenumber'   => '+4917662658919',
            'salary_type'   => 1,
            'salary_low'    => 1000.00
        ];

        try {
            $result = $project->submit($data);
            //echo Debug::vars($result);
        } catch (ORM_Validation_Eception $ex) {
            echo Debug::vars($ex->errors());
            echo Debug::vars($ex);
        } finally {
            echo Debug::vars(Session::instance()->get('validationErrors'));
            Session::instance()->delete('validationErrors');
        }
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
