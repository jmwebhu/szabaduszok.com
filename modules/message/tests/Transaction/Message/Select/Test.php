<?php

class Transaction_Message_Select_Test extends Unittest_TestCase
{
    protected $_users = [];
    protected $_conversations = [];
    protected $_messages = [];

    /**
     * @covers Transaction_Message_Select::getAllActiveBy()
     */
    public function testGetAllActiveByEveryMessage()
    {
        $this->markTestSkipped();
        $conversationId = $this->_conversations[0]->getId();
        $transaction    = new Transaction_Message_Select(new Model_Message());

        $activeMessages = $transaction->getAllActiveBy($conversationId);
        $this->assertEquals(6, count($activeMessages));

        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?', 'Közösségi oldal',
            'Bocsi, de az nem érdekel', 'szabadúszó pls...', 'pls...'
        ], $activeMessages);
    }

    /**
     * @covers Transaction_Message_Select::getAllActiveBy()
     */
    public function testGetAllActiveByHasDeletedByReceiver()
    {
        $this->markTestSkipped();
        $this->_messages[count($this->_messages) - 1]->deleteMessage(
            Entity_User::createUser($this->_users[0]->type, $this->_users[0]));

        $conversationId = $this->_conversations[0]->getId();
        $transaction    = new Transaction_Message_Select(new Model_Message());

        $activeMessages = $transaction->getAllActiveBy($conversationId);

        $this->assertEquals(5, count($activeMessages));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?', 'Közösségi oldal',
            'Bocsi, de az nem érdekel', 'szabadúszó pls...'
        ], $activeMessages);
    }

    /**
     * @covers Transaction_Message_Select::getAllActiveBy()
     */
    public function testGetAllActiveByHasDeletedBySender()
    {
        $this->markTestSkipped();
        $this->_messages[2]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $conversationId = $this->_conversations[0]->getId();
        $transaction    = new Transaction_Message_Select(new Model_Message());

        $activeMessages = $transaction->getAllActiveBy($conversationId);

        $this->assertEquals(5, count($activeMessages));
        $this->assertEqualsMessages([
            'Hello, ráérsz?', 'Hello, igen, mi a projekt?',
            'Bocsi, de az nem érdekel', 'szabadúszó pls...', 'pls...'
        ], $activeMessages);
    }

    /**
     * @covers Transaction_Message_Select::getAllToSenderDeletedByReceiver()
     */
    public function testGetAllToSenderDeletedByReceiverHasDeleted()
    {
        $this->markTestSkipped();
        $this->_messages[count($this->_messages) - 1]->deleteMessage(
            Entity_User::createUser($this->_users[0]->type, $this->_users[0]));

        $conversationId     = $this->_conversations[0]->getId();
        $transaction        = new Transaction_Message_Select(new Model_Message());

        $deletedMessages    = $transaction->getAllToSenderDeletedByReceiver($conversationId, $this->_users[1]->user_id);

        $this->assertEquals(1, count($deletedMessages));
        $this->assertEqualsMessages(['pls...'], $deletedMessages);
    }

    /**
     * @covers Transaction_Message_Select::getAllToSenderDeletedByReceiver()
     */
    public function testGetAllToSenderDeletedByReceiverNoDeleted()
    {
        $this->markTestSkipped();
        $this->_messages[count($this->_messages) - 1]->deleteMessage(
            Entity_User::createUser($this->_users[0]->type, $this->_users[0]));

        $conversationId     = $this->_conversations[0]->getId();
        $transaction        = new Transaction_Message_Select(new Model_Message());

        $deletedMessages    = $transaction->getAllToSenderDeletedByReceiver($conversationId, $this->_users[0]->user_id);

        $this->assertEquals(0, count($deletedMessages));
    }

    /**
     * @covers Transaction_Message_Select::getLastId()
     */
    public function testGetLastId()
    {
        $transaction    = new Transaction_Message_Select(new Model_Message());
        $lastId         = $this->invokeMethod($transaction, 'getLastId');

        $this->assertEquals($this->_messages[count($this->_messages) - 1]->getMessageId(), $lastId);
    }

    /**
     * @covers Transaction_Message_Select::getAllToReceiverDeletedBySender()
     */
    public function testGetAllToReceiverDeletedBySenderOneResultOneDeleted()
    {
        $this->markTestSkipped();
        $this->_messages[count($this->_messages) - 1]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $transaction    = new Transaction_Message_Select(new Model_Message());
        $messages       = $transaction->getLastToReceiverDeletedBySender(
            $this->_conversations[0]->getId(), $this->_users[0]->user_id);

        $this->assertEquals(1, count($messages));
        $this->assertMessagesDeleted($messages);
        $this->assertEqualsMessages(['pls...'], $messages);
    }

    /**
     * @covers Transaction_Message_Select::getAllToReceiverDeletedBySender()
     */
    public function testGetAllToReceiverDeletedBySenderOneResultMoreDeleted()
    {
        $this->markTestSkipped();
        $this->_messages[0]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $this->_messages[2]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $this->_messages[5]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $transaction    = new Transaction_Message_Select(new Model_Message());
        $messages       = $transaction->getLastToReceiverDeletedBySender(
            $this->_conversations[0]->getId(), $this->_users[0]->user_id);

        $this->assertEquals(1, count($messages));
        $this->assertMessagesDeleted($messages);
        $this->assertEqualsMessages(['pls...'], $messages);
    }

    /**
     * @covers Transaction_Message_Select::getAllToReceiverDeletedBySender()
     */
    public function testGetAllToReceiverDeletedBySenderMoreResultMoreDeleted()
    {
        $this->markTestSkipped();
        $this->_messages[4]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $this->_messages[5]->deleteMessage(
            Entity_User::createUser($this->_users[1]->type, $this->_users[1]));

        $transaction    = new Transaction_Message_Select(new Model_Message());
        $messages       = $transaction->getLastToReceiverDeletedBySender(
            $this->_conversations[0]->getId(), $this->_users[0]->user_id);

        $this->assertEquals(2, count($messages));
        $this->assertMessagesDeleted($messages);
        $this->assertEqualsMessages([
            'szabadúszó pls...', 'pls...'
        ], $messages);
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
        $transaction    = new Transaction_Message_Select(new Model_Message());
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

        $transaction    = new Transaction_Message_Select(new Model_Message());
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

        $transaction    = new Transaction_Message_Select(new Model_Message());
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

        $transaction    = new Transaction_Message_Select(new Model_Message());
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

        $transaction    = new Transaction_Message_Select(new Model_Message());
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

        $transaction    = new Transaction_Message_Select(new Model_Message());
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

        $transaction    = new Transaction_Message_Select(new Model_Message());
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

        $transaction    = new Transaction_Message_Select(new Model_Message());
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