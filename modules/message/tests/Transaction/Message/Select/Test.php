<?php

class Transaction_Message_Select_Test extends Unittest_TestCase
{
    protected $_users = [];
    protected $_conversations = [];
    protected $_messages = [];

    /**
     * @covers Transaction_Message_Select::getLastId()
     */
    public function testGetLastId()
    {
        $transaction    = Transaction_Message_Select_Factory::createSelect();
        $lastId         = $this->invokeMethod($transaction, 'getLastId');

        $this->assertEquals($this->_messages[count($this->_messages) - 1]->getMessageId(), $lastId);
    }

    /**
     * @covers Transaction_Message_Select::getAllToReceiverDeletedBySender()
     */
    public function testGetAllToReceiverDeletedBySenderOneResultOneDeleted()
    {
        $this->_messages[count($this->_messages) - 1]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $transaction    = Transaction_Message_Select_Factory::createSelect();
        $messages       = $transaction->getLastToReceiverDeletedBySender(
            $this->_conversations[0]->getId(), $this->_users[0]->user_id);

        $this->assertEquals(1, count($messages));
        $this->assertMessagesDeleted($messages);
        $this->assertEqualsMessages(['pls...'], $messages);

        $messagesByEmployer   = $transaction->getLastToReceiverDeletedBySender(
            $this->_conversations[0]->getId(), $this->_users[1]->user_id);

        $this->assertEquals(0, count($messagesByEmployer));
    }

    /**
     * @covers Transaction_Message_Select::getAllToReceiverDeletedBySender()
     */
    public function testGetAllToReceiverDeletedBySenderOneResultMoreDeleted()
    {
        $this->_messages[0]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $this->_messages[2]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $this->_messages[count($this->_messages) - 1]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $transaction    = Transaction_Message_Select_Factory::createSelect();
        $messages       = $transaction->getLastToReceiverDeletedBySender(
            $this->_conversations[0]->getId(), $this->_users[0]->user_id);

        $this->assertEquals(1, count($messages));
        $this->assertMessagesDeleted($messages);
        $this->assertEqualsMessages(['pls...'], $messages);

        $messagesByEmployer       = $transaction->getLastToReceiverDeletedBySender(
            $this->_conversations[0]->getId(), $this->_users[1]->user_id);

        $this->assertEquals(0, count($messagesByEmployer));
    }

    /**
     * @covers Transaction_Message_Select::getAllToReceiverDeletedBySender()
     */
    public function testGetAllToReceiverDeletedBySenderMoreResultMoreDeleted()
    {
        $this->_messages[7]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $this->_messages[8]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $transaction    = Transaction_Message_Select_Factory::createSelect();
        $messages       = $transaction->getLastToReceiverDeletedBySender(
            $this->_conversations[0]->getId(), $this->_users[0]->user_id);

        $this->assertEquals(2, count($messages));
        $this->assertMessagesDeleted($messages);
        $this->assertEqualsMessages([
            'szabadúszó pls...', 'pls...'
        ], $messages);

        $messagesByEmployer       = $transaction->getLastToReceiverDeletedBySender(
            $this->_conversations[0]->getId(), $this->_users[1]->user_id);
        $this->assertEquals(0, count($messagesByEmployer));
    }

    /**
     * @param Model_Message[] $messages
     */
    protected function assertMessagesDeleted(array $messages)
    {
        foreach ($messages as $message) {
            /**
             * @var $message Model_Message
             */
            $this->assertTrue($message->isDeleted);
        }
    }

    /**
     * @param string[] $expectedMessages
     * @param Model_Message[] $actualMessages
     */
    protected function assertEqualsMessages(array $expectedMessages, array $actualMessages)
    {
        foreach ($expectedMessages as $i => $expectedMessage) {
            $this->assertEquals($expectedMessage, $actualMessages[$i]->message);
        }
    }

    // ------------------------------- UJ TESZTEK -------------------------------

    /**
     * @covers Transaction_Message_Select::getAllVisibleBy()
     */
    public function testGetAllVisibleByAllMessagesVisible()
    {
        $transaction    = Transaction_Message_Select_Factory::createSelect();
        $messages       = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[0]);

        $this->assertEquals(9, count($messages));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?', 'Közösségi oldal', 'Olyan mint a FB', 'Csak sokkal jobb',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messages);

        $messagesByEmployer = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[1]);
        $this->assertEquals(9, count($messagesByEmployer));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?', 'Közösségi oldal', 'Olyan mint a FB', 'Csak sokkal jobb',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messagesByEmployer);
    }

    /**
     * @covers Transaction_Message_Select::getAllVisibleBy()
     */
    public function testGetAllVisibleByAllMessagesVisibleContainsDeletedByReceiverWhoIsNotUser()
    {
        // Fogado torolte, aki a masik felhasznalo
        $this->_messages[5]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $this->_messages[6]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $transaction    = Transaction_Message_Select_Factory::createSelect();
        $messages       = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[0]);

        $this->assertEquals(9, count($messages));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?', 'Közösségi oldal', 'Olyan mint a FB', 'Csak sokkal jobb',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messages);

        $messagesByEmployer = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[1]);

        $this->assertEquals(7, count($messagesByEmployer));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?', 'Közösségi oldal', 'Olyan mint a FB', 'Csak sokkal jobb',
            'szabadúszó pls...', 'pls...'
        ], $messagesByEmployer);
    }

    /**
     * @covers Transaction_Message_Select::getAllVisibleBy()
     */
    public function testGetAllVisibleByNotAllMessagesVisibleContainsOneDeletedBySenderWhoIsNotUser()
    {
        // Kuldo torolte, aki a masik felhasznalo
        $this->_messages[0]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $transaction    = Transaction_Message_Select_Factory::createSelect();
        $messages       = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[0]);

        $this->assertEquals(8, count($messages));
        $this->assertEqualsMessages([
            'Hello, igen, mi a projekt?', 'Közösségi oldal', 'Olyan mint a FB', 'Csak sokkal jobb',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messages);

        $messagesByEmployer = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[1]);

        $this->assertEquals(8, count($messagesByEmployer));
        $this->assertEqualsMessages([
            'Hello, igen, mi a projekt?', 'Közösségi oldal', 'Olyan mint a FB', 'Csak sokkal jobb',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messagesByEmployer);
    }

    /**
     * @covers Transaction_Message_Select::getAllVisibleBy()
     */
    public function testGetAllVisibleByNotAllMessagesVisibleContainsMoreDeletedBySenderWhoIsNotUser()
    {
        // Kuldo torolte, aki a masik felhasznalo
        $this->_messages[3]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $this->_messages[4]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $transaction    = Transaction_Message_Select_Factory::createSelect();
        $messages       = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[0]);

        $this->assertEquals(7, count($messages));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?', 'Közösségi oldal',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messages);

        $messagesByEmployer = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[1]);

        $this->assertEquals(7, count($messagesByEmployer));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?', 'Közösségi oldal',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messagesByEmployer);
    }

    /**
     * @covers Transaction_Message_Select::getAllVisibleBy()
     */
    public function testGetAllVisibleByNotAllMessagesVisibleContainsOneDeletedBySenderWhoIsUser()
    {
        // Kuldo torolte, aki a user
        $this->_messages[1]->deleteMessage(
            Entity_User::createUser($this->_users[0]->type, $this->_users[0]));

        $transaction    = Transaction_Message_Select_Factory::createSelect();
        $messages       = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[0]);

        $this->assertEquals(8, count($messages));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Közösségi oldal', 'Olyan mint a FB', 'Csak sokkal jobb',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messages);

        $messagesByEmployer = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[1]);

        $this->assertEquals(8, count($messagesByEmployer));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Közösségi oldal', 'Olyan mint a FB', 'Csak sokkal jobb',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messagesByEmployer);
    }

    /**
     * @covers Transaction_Message_Select::getAllVisibleBy()
     */
    public function testGetAllVisibleByNotAllMessagesVisibleContainsMoreDeletedBySenderWhoIsUser()
    {
        // Kuldo torolte, aki a user
        $this->_messages[1]->deleteMessage(
            Entity_User::createUser($this->_users[0]->type, $this->_users[0]));

        $this->_messages[6]->deleteMessage(
            Entity_User::createUser($this->_users[0]->type, $this->_users[0]));

        $transaction    = Transaction_Message_Select_Factory::createSelect();
        $messages       = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[0]);

        $this->assertEquals(7, count($messages));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Közösségi oldal', 'Olyan mint a FB', 'Csak sokkal jobb',
            'Bocsi, de az nem érdekel', 'szabadúszó pls...', 'pls...'
        ], $messages);

        $messagesByEmployer = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[1]);

        $this->assertEquals(7, count($messagesByEmployer));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Közösségi oldal', 'Olyan mint a FB', 'Csak sokkal jobb',
            'Bocsi, de az nem érdekel', 'szabadúszó pls...', 'pls...'
        ], $messagesByEmployer);
    }

    /**
     * @covers Transaction_Message_Select::getAllVisibleBy()
     */
    public function testGetAllVisibleByNotAllMessagesVisibleContainsMoreDeletedByReceiverWhoIsUser()
    {
        // Fogado torolte, aki a user
        $this->_messages[2]->deleteMessage(
            Entity_User::createUser($this->_users[0]->type, $this->_users[0]));

        $this->_messages[4]->deleteMessage(
            Entity_User::createUser($this->_users[0]->type, $this->_users[0]));

        $transaction    = Transaction_Message_Select_Factory::createSelect();
        $messages       = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[0]);

        $this->assertEquals(7, count($messages));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?', 'Olyan mint a FB',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messages);

        $messagesByEmployer = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[1]);

        $this->assertEquals(9, count($messagesByEmployer));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?', 'Közösségi oldal', 'Olyan mint a FB', 'Csak sokkal jobb',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messagesByEmployer);
    }

    /**
     * @covers Transaction_Message_Select::getAllVisibleBy()
     */
    public function testGetAllVisibleByNotAllMessagesVisibleContainsOneDeletedByReceiverWhoIsUser()
    {
        // Fogado torolte, aki a user
        $this->_messages[2]->deleteMessage(
            Entity_User::createUser($this->_users[0]->type, $this->_users[0]));

        $transaction    = Transaction_Message_Select_Factory::createSelect();
        $messages       = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[0]);

        $this->assertEquals(8, count($messages));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?', 'Olyan mint a FB', 'Csak sokkal jobb',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messages);

        $messagesByEmployer       = $transaction->getAllVisibleBy($this->_conversations[0]->getId(), $this->_users[1]);

        $this->assertEquals(9, count($messagesByEmployer));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?', 'Közösségi oldal', 'Olyan mint a FB', 'Csak sokkal jobb',
            'Bocsi, de az nem érdekel', 'Elég mainstreamnek hangzik', 'szabadúszó pls...', 'pls...'
        ], $messagesByEmployer);
    }

    /**
     * @covers Transaction_Message_Select::hasUnreadMessage()
     */
    public function testHasUnreadMessageHas()
    {       
        $transactionUnreadFreelancer    = new Transaction_Message_Count_Unread($this->_conversations[0]->getModel(), $this->_users[0]->user_id);
        $transactionUnreadEmployer      = new Transaction_Message_Count_Unread($this->_conversations[0]->getModel(), $this->_users[1]->user_id);
        $transactionSelect              = new Transaction_Message_Select(new Model_Message());

        $freelancerHas                  = $transactionSelect->hasUnreadMessage($transactionUnreadFreelancer);
        $employerHas                    = $transactionSelect->hasUnreadMessage($transactionUnreadEmployer);

        $this->assertTrue($freelancerHas);
        $this->assertTrue($employerHas);
    }
    
    /**
     * @covers Transaction_Message_Select::hasUnreadMessage()
     */
    public function testHasUnreadMessageHasNo()
    {       
        DB::update('message_interactions')->set(['is_readed' => 1])->execute();

        $transactionUnreadFreelancer    = new Transaction_Message_Count_Unread($this->_conversations[0]->getModel(), $this->_users[0]->user_id);
        $transactionUnreadEmployer      = new Transaction_Message_Count_Unread($this->_conversations[0]->getModel(), $this->_users[1]->user_id);
        $transactionSelect              = new Transaction_Message_Select(new Model_Message());

        $freelancerHas                  = $transactionSelect->hasUnreadMessage($transactionUnreadFreelancer);
        $employerHas                    = $transactionSelect->hasUnreadMessage($transactionUnreadEmployer);

        $this->assertFalse($freelancerHas);
        $this->assertFalse($employerHas);
    }

    /**
     * @covers Transaction_Message_Select::isThisFirstMessageTo()
     */
    public function testIsThisFirstMessageToNotOk()
    {
        $transactionSelect  = new Transaction_Message_Select(
            $this->_messages[count($this->_messages) - 1]->getModel());

        $isFirst            = $this->invokeMethod(
            $transactionSelect, 
            'isThisFirstMessageTo', 
            [$this->_users[0]->user_id]
        );

        $this->assertFalse($isFirst);

        $transactionSelect  = new Transaction_Message_Select(
            $this->_messages[5]->getModel());

        $isFirst            = $this->invokeMethod(
            $transactionSelect, 
            'isThisFirstMessageTo', 
            [$this->_users[1]->user_id]
        );

        $this->assertFalse($isFirst);
    }
    
    /**
     * @covers Transaction_Message_Select::isThisFirstMessageTo()
     */
    public function testIsThisFirstMessageToOk()
    {
        $freelancer = new Model_User_Freelancer();
        $freelancer->lastname       = 'Joó';
        $freelancer->firstname      = 'Martin';
        $freelancer->email          = uniqid() . '@gmail.com';
        $freelancer->password       = 'Password123';
        $freelancer->min_net_hourly_wage       = '3000';
        $freelancer->type = Entity_User::TYPE_FREELANCER;
        
        $freelancer->save();


        $employer = new Model_User_Employer();
        $employer->lastname       = 'Kis';
        $employer->firstname      = 'Pista';
        $employer->address_postal_code      = '9700';
        $employer->address_city      = 'Szombathely';
        $employer->email          = uniqid() . '@gmail.com';
        $employer->phonenumber          = '06301923380';
        $employer->password       = 'Password123';
        $employer->type = Entity_User::TYPE_EMPLOYER;

        $employer->save();

        $data = [
            'users' => [$freelancer->user_id, $employer->user_id]
        ];

        $conversation = new Entity_Conversation;
        $conversation->submit($data);

        $data = [
            'message'           => 'Első',
            'sender_id'         => $employer->user_id,
            'conversation_id'   => $conversation->getConversationId()
        ];

        $message = new Entity_Message();
        $message->submit($data);

        $transactionSelect  = new Transaction_Message_Select(
            $message->getModel());

        $isFirst            = $this->invokeMethod(
            $transactionSelect, 
            'isThisFirstMessageTo', 
            [$freelancer->user_id]
        );

        $this->assertTrue($isFirst);

        $transactionSelect  = new Transaction_Message_Select(
            $message->getModel());

        $isFirst            = $this->invokeMethod(
            $transactionSelect, 
            'isThisFirstMessageTo', 
            [$employer->user_id]
        );

        $this->assertFalse($isFirst);

        $conversation->getModel()->delete();
        $message->getModel()->delete();
        $freelancer->delete();
        $employer->delete();
    }

    public function setUp()
    {
        $this->setUpUsers();
        $this->setUpConversations();
    }

    protected function setUpConversations()
    {
        self::createConversation([$this->_users[0]->user_id, $this->_users[1]->user_id]);
        self::createConversation([$this->_users[0]->user_id, $this->_users[2]->user_id]);
        self::createConversation([$this->_users[0]->user_id, $this->_users[3]->user_id]);
        self::createConversation([$this->_users[0]->user_id, $this->_users[4]->user_id]);
        self::createConversation([$this->_users[5]->user_id, $this->_users[4]->user_id]);

        // 9 db
        self::sendMessage($this->_users[1]->user_id, $this->_conversations[0]->getId(), 'Hello, ráérsz?');
        self::sendMessage($this->_users[0]->user_id, $this->_conversations[0]->getId(), 'Hello, igen, mi a projekt?');
        self::sendMessage($this->_users[1]->user_id, $this->_conversations[0]->getId(), 'Közösségi oldal');
        self::sendMessage($this->_users[1]->user_id, $this->_conversations[0]->getId(), 'Olyan mint a FB');
        self::sendMessage($this->_users[1]->user_id, $this->_conversations[0]->getId(), 'Csak sokkal jobb');
        self::sendMessage($this->_users[0]->user_id, $this->_conversations[0]->getId(), 'Bocsi, de az nem érdekel');
        self::sendMessage($this->_users[0]->user_id, $this->_conversations[0]->getId(), 'Elég mainstreamnek hangzik');
        self::sendMessage($this->_users[1]->user_id, $this->_conversations[0]->getId(), 'szabadúszó pls...');
        self::sendMessage($this->_users[1]->user_id, $this->_conversations[0]->getId(), 'pls...');
    }

    /**
     * @param array $users
     * @param string $type
     * @param int $deleterIndex
     */
    protected function createConversation(array $users)
    {
        $data = [
            'users' => $users
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        $this->_conversations[] = $conversation;
    }

    /**
     * @param int $senderId
     * @param int $conversationId
     * @param string $message
     */
    protected function sendMessage($senderId, $conversationId, $message)
    {
        $data = [
            'message'           => $message,
            'sender_id'         => $senderId,
            'conversation_id'   => $conversationId
        ];

        $message = new Entity_Message();
        $message->submit($data);

        $this->_messages[] = $message;
    }

    protected function setUpUsers()
    {
        $freelancer = new Model_User_Freelancer();
        $freelancer->lastname       = 'Joó';
        $freelancer->firstname      = 'Martin';
        $freelancer->email          = uniqid() . '@gmail.com';
        $freelancer->password       = 'Password123';
        $freelancer->min_net_hourly_wage       = '3000';
        $freelancer->type = Entity_User::TYPE_FREELANCER;

        $freelancer->save();


        $employer = new Model_User_Employer();
        $employer->lastname       = 'Kis';
        $employer->firstname      = 'Pista';
        $employer->address_postal_code      = '9700';
        $employer->address_city      = 'Szombathely';
        $employer->email          = uniqid() . '@gmail.com';
        $employer->phonenumber          = '06301923380';
        $employer->password       = 'Password123';
        $employer->type = Entity_User::TYPE_EMPLOYER;

        $employer->save();

        $employer1 = new Model_User_Employer();
        $employer1->lastname       = 'Nagy';
        $employer1->firstname      = 'Béla';
        $employer1->address_postal_code      = '9700';
        $employer1->address_city      = 'Szombathely';
        $employer1->email          = uniqid() . '@gmail.com';
        $employer1->phonenumber          = '06301923380';
        $employer1->password       = 'Password123';
        $employer1->type = Entity_User::TYPE_EMPLOYER;

        $employer1->save();

        $employer2 = new Model_User_Employer();
        $employer2->lastname       = 'Horváth';
        $employer2->firstname      = 'Péter';
        $employer2->address_postal_code      = '9700';
        $employer2->address_city      = 'Szombathely';
        $employer2->email          = uniqid() . '@gmail.com';
        $employer2->phonenumber          = '06301923380';
        $employer2->password       = 'Password123';
        $employer2->type = Entity_User::TYPE_EMPLOYER;

        $employer2->save();

        $employer3 = new Model_User_Employer();
        $employer3->lastname       = 'Lukács';
        $employer3->firstname      = 'Laci';
        $employer3->address_postal_code      = '9700';
        $employer3->address_city      = 'Szombathely';
        $employer3->email          = uniqid() . '@gmail.com';
        $employer3->phonenumber          = '06301923380';
        $employer3->password       = 'Password123';
        $employer3->type = Entity_User::TYPE_EMPLOYER;

        $employer3->save();

        $freelancer1 = new Model_User_Freelancer();
        $freelancer1->lastname       = 'Joó';
        $freelancer1->firstname      = 'Martin';
        $freelancer1->email          = uniqid() . '@gmail.com';
        $freelancer1->password       = 'Password123';
        $freelancer1->min_net_hourly_wage       = '3000';
        $freelancer1->type = Entity_User::TYPE_FREELANCER;

        $freelancer1->save();

        $this->_users[] = $freelancer;
        $this->_users[] = $employer;
        $this->_users[] = $employer1;
        $this->_users[] = $employer2;
        $this->_users[] = $employer3;
        $this->_users[] = $freelancer1;
    }

    public function tearDown()
    {
        foreach ($this->_users as $user) {
            DB::delete('users')->where('user_id', '=', $user->user_id)->execute();
        }

        foreach ($this->_conversations as $item) {
            DB::delete('conversations')->where('conversation_id', '=', $item->getId())->execute();
        }

        foreach ($this->_messages as $message) {
            DB::delete('messages')->where('message_id', '=', $message->getMessageId())->execute();
        }
    }
}