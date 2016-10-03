<?php

class PagerTest extends Unittest_TestCase
{
    /**
     * @var stdClass
     */
    private $_context;

    /**
     * @covers Controller_Project_List::setContextPager()
     */
    public function testPagerOnePage()
    {
        $this->setMockAny('Entity_Project', 'getCount', 6);
        $controller = new Controller_Project_List(Request::factory(), new Response());

        $testPagerData = [
            'limit'         => 10,
            'currentPage'   => 1,
            'offset'        => 0
        ];

        $this->invokeMethod($controller, 'setProject', [$this->_mock]);
        $this->invokeMethod($controller, 'setPagerData', [$testPagerData]);
        $this->invokeMethod($controller, 'setContextPager', []);

        $this->_context = $controller->context;

        $this->assertEquals(1, $this->_context->pagesCount);
        $this->assertEquals([1], $this->_context->pages);
        $this->assertEquals(1, $this->_context->currentPage);
        $this->assertEquals(6, $this->_context->countAllProjects);
        $this->assertEquals(1, $this->_context->nextPage);
        $this->assertEquals(1, $this->_context->prevPage);
    }

    /**
 * @covers Controller_Project_List::setContextPager()
 */
    public function testPagerTwoPage()
    {
        $this->setMockAny('Entity_Project', 'getCount', 13);
        $controller = new Controller_Project_List(Request::factory(), new Response());

        $testPagerData = [
            'limit'         => 10,
            'currentPage'   => 1,
            'offset'        => 0
        ];

        $this->invokeMethod($controller, 'setProject', [$this->_mock]);
        $this->invokeMethod($controller, 'setPagerData', [$testPagerData]);
        $this->invokeMethod($controller, 'setContextPager', []);

        $this->_context = $controller->context;

        $this->assertEquals(2, $this->_context->pagesCount);
        $this->assertEquals([1, 2], $this->_context->pages);
        $this->assertEquals(1, $this->_context->currentPage);
        $this->assertEquals(13, $this->_context->countAllProjects);
        $this->assertEquals(2, $this->_context->nextPage);
        $this->assertEquals(1, $this->_context->prevPage);
    }

    /**
     * @covers Controller_Project_List::setContextPager()
     */
    public function testPagerThreePageCurrentPageOther()
    {
        $this->setMockAny('Entity_Project', 'getCount', 28);
        $controller = new Controller_Project_List(Request::factory(), new Response());

        $testPagerData = [
            'limit'         => 10,
            'currentPage'   => 2,
            'offset'        => 0
        ];

        $this->invokeMethod($controller, 'setProject', [$this->_mock]);
        $this->invokeMethod($controller, 'setPagerData', [$testPagerData]);
        $this->invokeMethod($controller, 'setContextPager', []);

        $this->_context = $controller->context;

        $this->assertEquals(3, $this->_context->pagesCount);
        $this->assertEquals([1, 2, 3], $this->_context->pages);
        $this->assertEquals(2, $this->_context->currentPage);
        $this->assertEquals(28, $this->_context->countAllProjects);
        $this->assertEquals(3, $this->_context->nextPage);
        $this->assertEquals(1, $this->_context->prevPage);
    }
}