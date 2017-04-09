<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller_DefaultTemplate
{
    public function action_index()
    {
        $sub = new Model_Subscription(1);
        echo Debug::vars($sub->object());
        exit;
    }
    
    public function action_generatemessagesfreelancer()
    {
        return false;
        /* $offset = $this->request->param('offset'); */
        /* $limit = 50; */

        /* $users = DB::select('user_id', 'firstname') */
        /*     ->from('users') */
        /*     ->where('user_id', '!=', 1) */
        /*     ->and_where('type', '=', 1) */
        /*     ->offset($limit * ($offset - 1))->limit($limit) */
        /*     ->execute()->as_array(); */

        /* $count = 0; */

        /* foreach ($users as $user) { */
        /*     $data = [ */
        /*         'users'     => [1, $user['user_id']] */
        /*     ]; */

        /*     $conversation = new Entity_Conversation(); */
        /*     $conversation->submit($data); */

            /* $message = 'Szia ' . $user['firstname'] . '! Örömmel értesítelek, hogy mostantól elérhető az oldalon belüli kapcsolatfelvétel, így ezentúl a Megbízók privát üzenetben tudnak megkeresni. Ezenkívül Te is tudsz nekik üzenetet küldeni egy projekt kapcsán. Az üzenetekről értesíteni fogunk, így nem maradsz le semmiről. Ha üzenetet szeretnél írni, menj a felhasználó profiljára, és kattints a \'Kapcsolatfelvétel\' gombra, vagy válaszd ki az \'Üzenetek\' menüt, majd keresd meg a felhasználót. Sok sikert! Üdv, Joó Martin'; */

            /* // Uzenet kuldese */
            /* $data = [ */
            /*     'message'           => $message, */
            /*     'sender_id'         => 1, */
            /*     'conversation_id'   => $conversation->getId() */
            /* ]; */

            /* $message = new Entity_Message(); */
            /* $message->submit($data); */

            /* $count++; */
        /* } */

        /* echo Debug::vars($count . ' conversations created'); */
        /* exit; */

    }

    public function action_generatemessagesemployer()
    {
        return false;
        /* $offset = $this->request->param('offset'); */
        /* $limit = 50; */

        /* $users = DB::select('user_id', */ 
        /*         [DB::expr('IF(is_company, company_name, firstname)'), 'name'], */
        /*         'is_company' */
        /*     ) */
        /*     ->from('users') */
        /*     ->where('user_id', '!=', 1) */
        /*     ->and_where('type', '=', 2) */
            /* ->offset($limit * ($offset - 1))->limit($limit) */
            /* ->execute()->as_array(); */

        /* $count = 0; */

        /* foreach ($users as $user) { */
            /* $data = [ */
            /*     'users'     => [1, $user['user_id']] */
            /* ]; */

            /* $conversation = new Entity_Conversation(); */
            /* $conversation->submit($data); */

            /* $invocation = ($user['is_company']) ? 'Kedves' : 'Szia'; */
            /* $message = $invocation . ' ' . $user['name'] . '! Örömmel értesítelek, hogy mostantól elérhető az oldalon belüli kapcsolatfelvétel, így nem kell többé e-maileket küldened a Szabadúszóknak. Egyszerűen menj a profiljára és kattints a \'Kapcsolatfelvétel\' gombra. Vagy menj az \'Üzenetek\' menüre, és keresd ki a megfelelő embert. Az elküldött üzenetről e-mailben értesítjük a címzettet, így garantáltan meg fogja kapni. A válaszról Téged is értesítünk, így nem maradsz le semmiről. Sok sikert! Üdv, Joó Martin'; */

            /* // Uzenet kuldese */
            /* $data = [ */
            /*     'message'           => $message, */
            /*     'sender_id'         => 1, */
            /*     'conversation_id'   => $conversation->getId() */
            /* ]; */

            /* $message = new Entity_Message(); */
            /* $message->submit($data); */

/*             $count++; */
/*         } */

/*         echo Debug::vars($count . ' conversations created'); */
/*         exit; */
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
