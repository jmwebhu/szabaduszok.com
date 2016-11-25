<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $sender     = Entity_User::createUser(2, 1);
        $receiver   = Entity_User::createUser(2, 2);

        $message = new Model_Message();
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setMessage('Hello');

        $message->send();
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
        $delete = Cache::instance()->delete_all();
        var_dump($delete);
    }
}
