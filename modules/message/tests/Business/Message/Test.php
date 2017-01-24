<?php

class Business_Message_Test extends Unittest_TestCase
{
    protected static $_users;
    protected static $_conversations;
    protected static $_messages;
    

    /**
     * @covers Business_Message::getIndexBeforeIdNotContinous()
     */
    public function testGetIndexBeforeIdNotContinous()
    {
        $continousIds = [1, 2, 3, 4, 5];
        $continousMessages = $this->getMessagesArray($continousIds);

        $notContinousIdsOneResult = [11, 12, 15, 16, 19];
        $notContinousMessagesOneResult = $this->getMessagesArray($notContinousIdsOneResult);

        $notContinousIdsMoreResult = [23, 24, 26, 27, 28];
        $notContinousMessagesMoreResult = $this->getMessagesArray($notContinousIdsMoreResult);

        $notContinousIdsMoreResultFirst = [33, 34];
        $notContinousMessagesMoreResultFirst = $this->getMessagesArray($notContinousIdsMoreResultFirst);

        $this->assertEquals(1, Business_Message::getIndexBeforeIdNotContinous($continousMessages));
        $this->assertEquals(4, Business_Message::getIndexBeforeIdNotContinous($notContinousMessagesOneResult));
        $this->assertEquals(2, Business_Message::getIndexBeforeIdNotContinous($notContinousMessagesMoreResult));
        $this->assertEquals(0, Business_Message::getIndexBeforeIdNotContinous($notContinousMessagesMoreResultFirst));
    }

    /**
     * @covers Business_Message::groupGivenMessagesByTextifiedDays()
     */
    public function testGroupGivenMessagesByTextifiedDaysModels()
    {
        $data                       = $this->getMessagesForGroup();
        $data['groupedMessages']    = Business_Message::groupGivenMessagesByTextifiedDays($data['messages']);

        $this->assertForGroup($data);        
    }

    /**
     * @covers Business_Message::groupGivenMessagesByTextifiedDays()
     */
    public function testGroupGivenMessagesByTextifiedDaysEntities()
    {
        $data                       = $this->getMessagesForGroup('entity');
        $data['groupedMessages']    = Business_Message::groupGivenMessagesByTextifiedDays($data['messages']);

        $this->assertForGroup($data);    
    }

    /**
     * @covers Business_Message::getLastDeletedFrom()
     */
    public function testGetLastDeletedFromMoreResultOk()
    {
       //  $models = [];
       //  foreach (self::$_messages as $i => $message) {
       //     $models[] = $message->getModel();
       // }   

       // $deletedMessages = Business_Message::getLastDeletedFrom($models);
       // $ids = [];
       
       // foreach ($deletedMessages as $deleted) {
       //      $ids[] = $deleted->message_id;
       // }

       // $this->assertMessageIdsEqual(
       //      [self::$_messages[2]->getId(), self::$_messages[3]->getId()], $ids);

       // $this->assertEquals(2, count($ids));
       // $this->assertTrue($deletedMessages[0]->isDeleted);
    }

    protected function assertMessageIdsEqual(array $expectedIds, array $actualIds)
    {
        // $this->assertEquals(count($expectedIds), count($actualIds));
        // foreach ($expectedIds as $id) {
        //     $this->assertInArray($id, $actualIds);
        // }
    }
    

    /**
     * @covers Business_Message::getLastDeletedFrom()
     */
    public function testGetLastDeletedFromNoResultOk()
    {
       // $deletedMessages = Business_Message::getLastDeletedFrom([]);
       // $this->assertEquals([], $deletedMessages);
       // $this->assertEquals(0, count($deletedMessages));
    }

    /**
     * @param  array  $data
     * @return void
     */
    protected function assertForGroup(array $data)
    {
        foreach ($data['days'] as $j => $key) {
            if ($j == 0) {
                $this->assertEquals($data['messages'][$j]->message_id, $data['groupedMessages']['ma'][0]->message_id);
            } elseif ($j == 1) {
                $this->assertEquals($data['messages'][$j]->message_id, $data['groupedMessages']['tegnap'][0]->message_id);
            } elseif ($j <= Date::$_textifyMaxInterval) {
                $this->assertEquals($data['messages'][$j]->message_id, $data['groupedMessages'][__($key)][0]->message_id);
            } else {
                $date = date('m.d', $data['timestamps'][$j]);
                $this->assertEquals($data['messages'][$j]->message_id, $data['groupedMessages'][$date][0]->message_id);
            }
        }
    }
    
    /**
     * @param  string $type
     * @return array
     */
    protected function getMessagesForGroup($type = 'model')
    {
        $now            = date('Y-m-d', time());
        $timestamps     = [];
        $days           = [];
        $messages       = [];

        for ($i = 0; $i < 20; $i++) {
            $timestamp      = strtotime($now . '-' . $i . ' days');
            $timestamps[]   = $timestamp;
            $dateTime       = new DateTime(date('Y-m-d', $timestamp));
            $days[]         = $dateTime->format('l');

            $message                = new Model_Message();
            $message->created_at    = $dateTime->format('Y-m-d');
            $message->message_id    = $i + 1;

            $messages[]             = $message;
        }

        if ($type = 'entity') {
            $entity     = new Entity_Message;
            $messsages  = $entity->getEntitiesFromModels($messages);
        }

        return [
            'messages'      => $messages,
            'days'          => $days,
            'timestamps'    => $timestamps
        ];
    }
    

    /**
     * @param array $ids
     * @return int[]
     */
    protected function getMessagesArray(array $ids)
    {
        $messages = [];
        foreach ($ids as $id) {
            $message                = new Model_Message();
            $message->message_id    = $id;
            $message->message       = 'asd';

            $messages[] = $message;
        }

        return $messages;
    }

    public static function setUpBeforeClass()
    {
        self::setUpUsers();
        self::setUpConversations();
    }

    protected static function setUpConversations()
    {
        $data = [
            'users' => [self::$_users[0]->user_id, self::$_users[1]->user_id]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        self::$_conversations[] = $conversation;

        $data = [
            'message'           => 'Első',
            'sender_id'         => self::$_users[1]->user_id,
            'conversation_id'   => $conversation->getId()            
        ];

        $message = new Entity_Message;
        $message->send($data);

        $data = [
            'message'           => 'Második',
            'sender_id'         => self::$_users[0]->user_id,
            'conversation_id'   => $conversation->getId()            
        ];

        $message1 = new Entity_Message;
        $message1->send($data);

        $data = [
            'message'           => 'Harmadik',
            'sender_id'         => self::$_users[1]->user_id,
            'conversation_id'   => $conversation->getId()            
        ];

        $message2 = new Entity_Message;
        $message2->send($data);

        $data = [
            'message'           => 'Negyedik',
            'sender_id'         => self::$_users[1]->user_id,
            'conversation_id'   => $conversation->getId()            
        ];

        $message3 = new Entity_Message;
        $message3->send($data);

        // Fogadott uzenet torlese
        $deleter = new Entity_User_Freelancer(self::$_users[0]);
        $message2->deleteMessage($deleter);
        $message3->deleteMessage($deleter);

        self::$_messages[] = $message;
        self::$_messages[] = $message1;
        self::$_messages[] = $message2;
        self::$_messages[] = $message3;
    }

    protected static function setUpUsers()
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

        self::$_users[] = $freelancer;
        self::$_users[] = $employer;
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$_users as $user) {
            DB::delete('users')->where('user_id', '=', $user->user_id)->execute();
        }

        foreach (self::$_conversations as $conversation) {
            DB::delete('conversations')->where('conversation_id', '=', $conversation->getId())->execute();
        }

        foreach (self::$_messages as $message) {
            DB::delete('messages')->where('message_id', '=', $message->getMessageId())->execute();
        }
    }
}