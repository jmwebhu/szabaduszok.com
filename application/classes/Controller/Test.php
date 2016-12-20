<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller_DefaultTemplate
{
    public function action_index()
    {
        $conversations   = Entity_Conversation::getForLeftPanelBy(Auth::instance()->get_user()->user_id);
        

        $conversations = Business_Conversation::putIntoFirstPlace($conversations, 'joo-martin-lakatos-david');

        foreach ($conversations as $conversation) {
            echo Debug::vars($conversation->getSlug());
        }

        exit;
        // $ownUserId = 1;
        // $userIds = DB::select('user_id')->from('users')->where('user_id', '!=', $ownUserId)->offset(0)->limit(100)->execute()->as_array();
        // $userIds = Arr::DBQueryConvertSingleArray($userIds);

        // foreach ($userIds as $userId) {
        //     $conversation = new Entity_Conversation();
        //     $conversation->submit(['users' => [$ownUserId, $userId]]);
        // }

        // $conversationIds = DB::select('conversation_id')->from('conversations')->offset(0)->limit(100)->execute()->as_array();
        // $conversationIds = Arr::DBQueryConvertSingleArray($conversationIds);

        // foreach ($conversationIds as $conversationId) {
        //     $data = [
        //         'message'           => 'Hello! Mi a helyzet?',
        //         'sender_id'         => $ownUserId,
        //         'conversation_id'   => $conversationId
        //     ];

        //     $message = new Entity_Message();
        //     $message->submit($data);
        // }
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

        // Uzenet kuldese
        $data = [
            'message'           => 'Megkaptam',
            'sender_id'         => 1,
            'conversation_id'   => 9
        ];

        $message = new Entity_Message();
        $message->submit($data);

        exit;

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

    public function action_clearsession()
    {
        Session::instance()->destroy();
    }
    
}
