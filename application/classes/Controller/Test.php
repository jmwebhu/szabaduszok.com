<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        try {
            /*$data = [
                'name'  => 'Joó Martin, Kis Pista',
                'users' => [2, 1]
            ];

            $conversation = new Entity_Conversation();
            $submit = $conversation->submit($data);*/

            $data = [
                'conversation_id'   => 18,
                'message'           => 'Teszt',
                'sender_id'         => 2
            ];

            $message = new Entity_Message();
            $message->submit($data);

        } catch (Exception $ex) {
            echo Debug::vars($ex->getMessage());
            echo Debug::vars($ex->getTraceAsString());
            exit;
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
        } catch (ORM_Validation_Exception $ex) {
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
