<?php defined('SYSPATH') or die('No direct script access.');

abstract class Unittest_TestCase extends Kohana_Unittest_TestCase 
{
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
}
