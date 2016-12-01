<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        // beszelgetes letrehozasa
        $data = [
            //'name'      => 'Teszt',
            'users'     => [1, 2]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        exit;
    }

    public function action_message()
    {
        // beszelgetes letrehozasa
        $data = [
            //'name'      => 'Teszt',
            'users'     => [1, 2]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        exit;

        // Uzenet kuldese
        $data = [
            'message'           => 'Megkaptam',
            'sender_id'         => 1,
            'conversation_id'   => 9
        ];

        $message = new Entity_Message();
        $message->submit($data);

        // Elkuldott uzenet torlese
        $deleter = new Entity_User_Freelancer(1);
        $message = new Entity_Message(4);
        $message->deleteMessage($deleter);

        // Fogadott uzenet torlese
        $deleter = new Entity_User_Freelancer(1);
        $message = new Entity_Message(3);
        $message->deleteMessage($deleter);

        // Beszelgetes torlese
        $deleter = new Entity_User_Freelancer(1);
        $conversation = new Entity_Conversation(9);
        $conversation->deleteConversation($deleter);

        $deleter = new Entity_User_Employer(2);
        $conversation = new Entity_Conversation(9);
        $conversation->deleteConversation($deleter);
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
