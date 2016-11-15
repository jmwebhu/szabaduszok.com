<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $project = new Entity_Project();
        $data = [
            'user_id'   => 2042,
            'name'      => 'Valid teszt',
            'email'     => 'martin@szabaduszok.com',
            'phonenumber'   => '06301923380',
            'salary_type'   => 1,
            'salary_low'    => 2000,
            'short_description' => 'Rövid leírás',
            'long_description'  => 'Hpsszú'
        ];

        try {
            $result = $project->submit($data);

        } catch (ORM_Validation_Exception $ex) {
            Session::instance()->set('validation_errors', $ex->errors('models'));
            Session::instance()->set('post', Input::post_all());
        } finally {
            echo Debug::vars(Session::instance()->get('validation_errors'));
            echo Debug::vars(Session::instance()->get('post'));
            Session::instance()->delete('validation_errors');
        }
    }

    public function action_user()
    {
        $user = new Entity_User_Freelancer();
        $data = [
            'is_company'            => 'on',
            'company_name'          => 'Szabaduszok.com Kft.',
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => 'joomartin' . time() . '@jmweb.hu',
            'password'              => 'Password1234',
            'password_confirm'      => 'Password1234',
            'address_postal_code'   => '1010',
            'address_city'          => 'Budapest',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás, kicsit hosszabb',
            'min_net_hourly_wage'   => '1200.00',
            'webpage'               => 'asdf'
        ];

        try {
            $result = $user->submitUser($data);
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
