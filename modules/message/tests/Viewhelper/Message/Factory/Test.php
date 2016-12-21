<?php

class Viewhelper_Message_Factory_Test extends Unittest_TestCase
{
    /**
     * @covers Viewhelper_Message_Factory::createViewhelper()
     */
    public function testCreateViewhelperOutgoing()
    {
        $model = new Model_Message;
        $model->sender_id = 1;

        $entity = new Entity_Message($model);

        $viewhelper = Viewhelper_Message_Factory::createViewhelper($entity, 1);
        $this->assertTrue($viewhelper instanceof Viewhelper_Message_Outgoing);
    }

    /**
     * @covers Viewhelper_Message_Factory::createType()
     */
    public function testCreateTypeIncoming()
    {
        $model = new Model_Message;
        $model->sender_id = 2;

        $entity = new Entity_Message($model);

        $viewhelper = Viewhelper_Message_Factory::createViewhelper($entity, 1);
        $this->assertTrue($viewhelper instanceof Viewhelper_Message_Incoming);
    }
}