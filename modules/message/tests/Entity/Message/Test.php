<?php

class Entity_Messge_Test extends Unittest_TestCase
{
    protected static $_users = [];

    protected static $_conversation = null;

    /**
     * @covers Entity_Message::submit()
     */
    public function testSubmit()
    {
        $data = [
            'message'           => 'Hello',
            'sender_id'         => self::$_users[0]->user_id,
            'conversation_id'   => self::$_conversation->getConversationId()
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
     * @param int $messageId
     * @param int $userId
     * @param array $flags
     */
    protected function assertMessageInteractionExistsWithFlags($messageId, $userId, array $flags)
    {
        $interactions = DB::select()
            ->from('message_interactions')
            ->where('message_id', '=', $messageId)
            ->and_where('user_id', '=', $userId)
            ->and_where('is_deleted', '=', Arr::get($flags, 'is_deleted', 0))
            ->and_where('is_readed', '=', Arr::get($flags, 'is_readed', 0))
            ->execute()->current();

        $this->assertNotEmpty($interactions['message_interaction_id']);
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

        self::$_conversation = $conversation;
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$_users as $user) {
            DB::delete('users')->where('user_id', '=', $user->user_id)->execute();
        }

        DB::delete('conversations')->where('conversation_id', '=', self::$_conversation->getConversationId())->execute();
    }
}