<?php

class Viewhelper_Message_Type_Factory_Test extends Unittest_TestCase
{
    /**
     * @covers Viewhelper_Message_Type_Factory::createType()
     */
    public function testCreateTypeOutgoing()
    {
        $model = new Model_Message;
        $model->sender_id = 1;

        $entity = new Entity_Message($model);

        $type = Viewhelper_Message_Type_Factory::createType($entity, 1);
        $this->assertTrue($type instanceof Viewhelper_Message_Type_Outgoing);
    }

    /**
     * @covers Viewhelper_Message_Type_Factory::createType()
     */
    public function testCreateTypeIncoming()
    {
        $model = new Model_Message;
        $model->sender_id = 2;

        $entity = new Entity_Message($model);

        $type = Viewhelper_Message_Type_Factory::createType($entity, 1);
        $this->assertTrue($type instanceof Viewhelper_Message_Type_Incoming);
    }
}