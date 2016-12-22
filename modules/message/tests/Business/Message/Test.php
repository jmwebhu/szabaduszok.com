<?php

class Business_Message_Test extends Unittest_TestCase
{
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
}