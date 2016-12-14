<?php

class Viewhelper_Message_Outgoing_Test extends Unittest_TestCase
{
    /**
     * @var Entity_Message
     */
    protected static $_message;

    /**
     * @var Viewhelper_Message_Incoming
     */
    protected static $_viewhelper;    

    /**
     * @covers Viewhelper_Message_Incoming::getType()
     */
    public function testGetType()
    {
        $this->assertEquals(Viewhelper_Message::TYPE_OUTGOING, self::$_viewhelper->getType());
    }

    /**
     * @covers Viewhelper_Message_Incoming::getColor()
     */
    public function testGetColor()
    {
        $this->assertEquals(Viewhelper_Message::COLOR_OUTGOING, self::$_viewhelper->getColor());
    }

    public static function setUpBeforeClass()
    {
        $model = new Model_Message;
        $model->sender_id = 1;

        $entity = new Entity_Message($model);

        self::$_message = $entity;

        $vh = new Viewhelper_Message_Outgoing(self::$_message, 1);
        self::$_viewhelper = $vh;
    }
}