<?php

class Business_Message_Test extends Unittest_TestCase
{
    /**
     * @covers Business_Message::getIndexBeforeIdNotContinous()
     * @dataProvider getIndexBeforeIdNotContinousDataProvider
     */
    public function testGetIndexBeforeIdNotContinous($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Message::getIndexBeforeIdNotContinous()
     */
    public function testGroupGivenMessagesByDays()
    {
        $messageMonday1 = new Model_Message();
        $messageMonday1->created_at = '2016-12-05 14:37'; 
        $messageMonday1->message_id = 1;

        $messageMonday2 = new Model_Message();
        $messageMonday2->created_at = '2016-12-05 16:23';
        $messageMonday2->message_id = 2;

        $messageTuesday = new Model_Message();
        $messageTuesday->created_at = '2016-12-06 18:52';          
        $messageTuesday->message_id = 3;

        $messageThursday1 = new Model_Message();
        $messageThursday1->created_at = '2016-12-08 09:13';
        $messageThursday1->message_id = 4;

        $messageThursday2 = new Model_Message();
        $messageThursday2->created_at = '2016-12-08 10:05';
        $messageThursday2->message_id = 5;

        $groupedMessages = Business_Message::groupGivenMessagesByDays([$messageMonday1, $messageMonday2, $messageTuesday, $messageThursday1, $messageThursday2]);

        $this->assertEquals(['2016-12-05', '2016-12-06', '2016-12-08'], array_keys($groupedMessages));

        foreach ($groupedMessages['2016-12-05'] as $message) {
            $this->assertTrue(in_array($message->message_id, [1, 2]));
        }

        foreach ($groupedMessages['2016-12-06'] as $message) {
            $this->assertEquals($message->message_id, 3);
        }

        foreach ($groupedMessages['2016-12-08'] as $message) {
            $this->assertTrue(in_array($message->message_id, [4, 5]));
        }
    }
    
    

    public function getIndexBeforeIdNotContinousDataProvider()
    {
        $continousIds = [1, 2, 3, 4, 5];
        $continousMessages = $this->getMessagesArray($continousIds);

        $notContinousIdsOneResult = [11, 12, 15, 16, 19];
        $notContinousMessagesOneResult = $this->getMessagesArray($notContinousIdsOneResult);

        $notContinousIdsMoreResult = [23, 24, 26, 27, 28];
        $notContinousMessagesMoreResult = $this->getMessagesArray($notContinousIdsMoreResult);

        $notContinousIdsMoreResultFirst = [33, 34];
        $notContinousMessagesMoreResultFirst = $this->getMessagesArray($notContinousIdsMoreResultFirst);

        return [
            [1, Business_Message::getIndexBeforeIdNotContinous($continousMessages)],
            [4, Business_Message::getIndexBeforeIdNotContinous($notContinousMessagesOneResult)],
            [2, Business_Message::getIndexBeforeIdNotContinous($notContinousMessagesMoreResult)],
            [0, Business_Message::getIndexBeforeIdNotContinous($notContinousMessagesMoreResultFirst)]
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
}