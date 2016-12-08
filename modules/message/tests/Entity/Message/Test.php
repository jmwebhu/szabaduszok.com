<?php

class Entity_Messge_Test extends Unittest_TestCase
{
    protected static $_users = [];

    protected static $_conversations = [];

    protected static $_message = null;

    protected static $_messageIds = [];

    /**
     * @covers Entity_Message::submit()
     */
    public function testSubmit()
    {
        $data = [
            'message'           => 'Hello',
            'sender_id'         => self::$_users[0]->user_id,
            'conversation_id'   => self::$_conversations[0]->getConversationId()
        ];

        $message = new Entity_Message();
        $message->submit($data);

        $this->assertNotEmpty($message->getMessageId());
        $this->assertEquals($data['message'], $message->getMessage());
        $this->assertEquals($data['sender_id'], $message->getSenderId());
        $this->assertEquals($data['conversation_id'], $message->getConversationId());

        $this->assertMessageInteractionExistsWithFlags(
            $message->getMessageId(), self::$_users[0], ['is_readed' => 1, 'is_deleted' => 0]);

        $this->assertMessageInteractionExistsWithFlags(
            $message->getMessageId(), self::$_users[1], ['is_readed' => 0, 'is_deleted' => 0]);

        $message->delete();
    }

    /**
     * @covers Entity_Message::deleteMessage()
     */
    public function testDeleteMessageIncoming()
    {
        $sender     = self::$_users[0];
        $receiver   = self::$_users[1];

        $this->givenMessage($sender, $receiver, 'Megkeresés');
        $receiverEntity = Entity_User::createUser($receiver->type, $receiver);

        self::$_message->deleteMessage($receiverEntity);

        $this->assertMessageInteractionExistsWithFlags(
            self::$_message->getMessageId(), $receiverEntity->getUserId(), ['is_readed' => 0, 'is_deleted' => 1]);

        $this->assertMessageInteractionExistsWithFlags(
            self::$_message->getMessageId(), $sender->user_id, ['is_readed' => 1, 'is_deleted' => 0]);
    }

    /**
     * @covers Entity_Message::deleteMessage()
     */
    public function testDeleteMessageOutgoing()
    {
        $sender     = self::$_users[0];
        $receiver   = self::$_users[1];

        $this->givenMessage($sender, $receiver, 'Megkeresés');
        $senderEntity = Entity_User::createUser($sender->type, $sender);

        self::$_message->deleteMessage($senderEntity);

        $this->assertMessageInteractionExistsWithFlags(
            self::$_message->getMessageId(), $senderEntity->getUserId(), ['is_readed' => 1, 'is_deleted' => 1]);

        $this->assertMessageInteractionExistsWithFlags(
            self::$_message->getMessageId(), $receiver->user_id, ['is_readed' => 0, 'is_deleted' => 1]);
    }

    /**
     * @param int $messageId
     * @param int $userId
     * @param array $flags
     */
    protected function assertMessageInteractionExistsWithFlags($messageId, $userId, array $flags)
    {
        $interaction = DB::select()
            ->from('message_interactions')
            ->where('message_id', '=', $messageId)
            ->and_where('user_id', '=', $userId)
            ->and_where('is_deleted', '=', Arr::get($flags, 'is_deleted', 0))
            ->and_where('is_readed', '=', Arr::get($flags, 'is_readed', 0))
            ->execute()->current();

        $this->assertNotEmpty($interaction['message_interaction_id']);
    }

    public static function setUpBeforeClass()
    {
        self::setUpUsers();
        self::setUpConversation();
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

    protected static function setUpConversation()
    {
        $data = [
            'users' => [self::$_users[0]->user_id, self::$_users[1]->user_id]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        self::$_conversations[] = $conversation;
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$_users as $user) {
            DB::delete('users')->where('user_id', '=', $user->user_id)->execute();
        }

        foreach (self::$_conversations as $conversation) {
            DB::delete('conversations')->where('conversation_id', '=', $conversation->getConversationId())->execute();
        }

        foreach (self::$_messageIds as $id) {
            DB::delete('messages')->where('message_id', '=', $id)->execute();
        }
    }

    private function givenMessage(Model_User $sender, Model_User $receiver, $message)
    {
        $data = [
            'users' => [$sender->user_id, $receiver->user_id]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        self::$_conversations[] = $conversation;

        $data = [
            'message'           => $message,
            'sender_id'         => $sender->user_id,
            'conversation_id'   => $conversation->getConversationId()
        ];

        $message = new Entity_Message();
        $message->submit($data);

        self::$_message = $message;
        self::$_messageIds[] = $message->getMessageId();
    }
}