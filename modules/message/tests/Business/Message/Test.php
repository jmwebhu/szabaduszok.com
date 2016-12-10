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

        $groupedMessages    = Business_Message::groupGivenMessagesByTextifiedDays($messages);
        $keys               = $days;

        foreach ($keys as $j => $key) {
            if ($j == 0) {
                $this->assertEquals($messages[$j]->message_id, $groupedMessages['Today'][0]->message_id);
            } elseif ($j == 1) {
                $this->assertEquals($messages[$j]->message_id, $groupedMessages['Yesterday'][0]->message_id);
            } elseif ($j <= Date::$_textifyMaxInterval) {
                $this->assertEquals($messages[$j]->message_id, $groupedMessages[$key][0]->message_id);
            } else {
                $date = date('Y-m-d', $timestamps[$j]);
                $this->assertEquals($messages[$j]->message_id, $groupedMessages[$date][0]->message_id);
            }
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