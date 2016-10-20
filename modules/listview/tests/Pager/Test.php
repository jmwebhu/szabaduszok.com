<?php

class Pager_Test extends Unittest_TestCase
{
    /**
     * @var array
     */
    protected $_items = [];

    /**
     * @covers Pager::initPagesCount()
     * @covers Pager::getPagesCount()
     * @covers Pager::getNextPage()
     * @covers Pager::getPreviousPage()
     * @covers Pager::getPagesArray()
     */
    public function testPagerOnePage()
    {
        $this->setUpItems(6);
        $pager = new Pager($this->_items, 1, 10, URL::base());

        $this->assertEquals(1, $pager->getPagesCount());
        $this->assertEquals(1, $pager->getNextPage());
        $this->assertEquals(1, $pager->getPreviousPage());
        $this->assertEquals([1], $pager->getPagesArray());
    }

    /**
     * @covers Pager::initPagesCount()
     * @covers Pager::getPagesCount()
     * @covers Pager::getNextPage()
     * @covers Pager::getPreviousPage()
     * @covers Pager::getPagesArray()
     */
    public function testPagerTwoPage()
    {
        $this->setUpItems(13);
        $pager = new Pager($this->_items, 1, 10, URL::base());

        $this->assertEquals(2, $pager->getPagesCount());
        $this->assertEquals(2, $pager->getNextPage());
        $this->assertEquals(1, $pager->getPreviousPage());
        $this->assertEquals([1, 2], $pager->getPagesArray());
    }

    /**
     * @covers Pager::initPagesCount()
     * @covers Pager::getPagesCount()
     * @covers Pager::getNextPage()
     * @covers Pager::getPreviousPage()
     * @covers Pager::getPagesArray()
     */
    public function testPagerThreePageCurrentPageOther()
    {
        $this->setUpItems(28);
        $pager = new Pager($this->_items, 2, 10, URL::base());

        $this->assertEquals(3, $pager->getPagesCount());
        $this->assertEquals(3, $pager->getNextPage());
        $this->assertEquals(1, $pager->getPreviousPage());
        $this->assertEquals([1, 2, 3], $pager->getPagesArray());
    }

    /**
     * @covers Pager::initPagesCount()
     * @covers Pager::getPagesCount()
     * @covers Pager::getNextPage()
     * @covers Pager::getPreviousPage()
     * @covers Pager::getPagesArray()
     */
    public function testPagerFourPageCurrentPageOther()
    {
        $this->setUpItems(35);
        $pager = new Pager($this->_items, 3, 10, URL::base());

        $this->assertEquals(4, $pager->getPagesCount());
        $this->assertEquals(4, $pager->getNextPage());
        $this->assertEquals(2, $pager->getPreviousPage());
        $this->assertEquals([1, 2, 3, 4], $pager->getPagesArray());
    }

    /**
     * @covers Pager::initPagesCount()
     * @covers Pager::getPagesCount()
     * @covers Pager::getNextPage()
     * @covers Pager::getPreviousPage()
     * @covers Pager::getPagesArray()
     */
    public function testPagerFivePages()
    {
        $this->setUpItems(50);
        $pager = new Pager($this->_items, 1, 10, URL::base());

        $this->assertEquals(5, $pager->getPagesCount());
        $this->assertEquals(2, $pager->getNextPage());
        $this->assertEquals(1, $pager->getPreviousPage());
        $this->assertEquals([1, 2, 3, 4, 5], $pager->getPagesArray());
    }

    /**
     * @covers Pager::initPagesCount()
     * @covers Pager::getPagesCount()
     * @covers Pager::getNextPage()
     * @covers Pager::getPreviousPage()
     * @covers Pager::getPagesArray()
     */
    public function testPagerTenItems()
    {
        $this->setUpItems(10);
        $pager = new Pager($this->_items, 1, 10, URL::base());

        $this->assertEquals(1, $pager->getPagesCount());
        $this->assertEquals(1, $pager->getNextPage());
        $this->assertEquals(1, $pager->getPreviousPage());
        $this->assertEquals([1], $pager->getPagesArray());
    }

    /**
     * @covers Pager::initPagesCount()
     * @covers Pager::getPagesCount()
     * @covers Pager::getNextPage()
     * @covers Pager::getPreviousPage()
     * @covers Pager::getPagesArray()
     */
    public function testPagerElevenItems()
    {
        $this->setUpItems(11);
        $pager = new Pager($this->_items, 1, 10, URL::base());

        $this->assertEquals(2, $pager->getPagesCount());
        $this->assertEquals(2, $pager->getNextPage());
        $this->assertEquals(1, $pager->getPreviousPage());
        $this->assertEquals([1, 2], $pager->getPagesArray());
    }

    /**
     * @param int $count
     */
    protected function setUpItems($count)
    {
        for ($i = 0; $i < $count; $i++) {
            $this->_items[$i] = [];
        }
    }
}