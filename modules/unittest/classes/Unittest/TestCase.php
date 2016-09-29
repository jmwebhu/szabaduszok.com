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
        $mock  = $this->getMockBuilder($class)->getMock();

        $mock->expects($this->any())
            ->method($method)
            ->will($this->returnValue($return));

        $this->_mock = $mock;
    }
}
