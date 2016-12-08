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