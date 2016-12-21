<?php defined('SYSPATH') or die('No direct script access.');

abstract class Unittest_TestCase extends Kohana_Unittest_TestCase 
{
    protected $_mock = null;

    /**
    * protected / private metodus hivasa az adott objektumon
    *
    * @param object &$object    Peldanyositott objektum, abbol az osztalybol, ahol a protected / pricvate metodus van
    * @param string $methodName Metodus neve
    * @param array  $parameters Paramterek
    *
    * @return mixed 			 Metodus visszateresi erteke
    */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
       $reflection 	= new \ReflectionClass(get_class($object));
       $method 		= $reflection->getMethod($methodName);

       $method->setAccessible(true);

       return $method->invokeArgs($object, $parameters);
    }

    public function assertNotInArray($item, array $array)
    {
       $this->assertFalse(in_array($item, $array));
    }

    public function assertArrayNotSubset(array $subset, array $array)
    {
        foreach ($subset as $item) {
            $this->assertNotInArray($item, $array);
        }
    }

    public function setMockAny($class, $method, $return)
    {
        $mock = $this->_mock;
        if ($this->_mock == null) {
            $mock  = $this->getMockBuilder($class)->getMock();
        }

        $mock->expects($this->any())
            ->method($method)
            ->will($this->returnValue($return));

        $this->_mock = $mock;
    }

    public function createMock($class, $method, $return)
    {
        $mock  = $this->getMockBuilder($class)->getMock();

        $mock->expects($this->any())
            ->method($method)
            ->will($this->returnValue($return));

        return $mock;
    }

    /**
     * @param array $data
     */
    protected function assertNotificationExistsInDatabaseWith(array $data)
    {
        $notification = DB::select()
            ->from('notifications')
            ->where('notifier_user_id', '=', $data['notifier_user_id'])
            ->where('notified_user_id', '=', $data['notified_user_id'])
            ->where('subject_id', '=', $data['subject_id'])
            ->where('event_id', '=', $data['event_id'])
            ->limit(1)
            ->execute()->current();

        $this->assertNotNull($notification['notification_id']);
        $this->assertNotEmpty($notification['notification_id']);
        $this->assertTrue($notification['is_archived'] != 1);
    }
}
