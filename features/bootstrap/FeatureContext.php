<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Behat\Mink\Element\TraversableElement;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;


/**
 * Defines application features from the specific context.
 */
class FeatureContext extends PHPUnit_Framework_TestCase implements Context, SnippetAcceptingContext
{
    protected $_url         = 'http://127.0.0.1/v21.szabaduszok.com/';
    protected $_driver;
    
    protected static $_session;
    
    /**
     * @var DocumentElement
     */
    protected $_page; 
    
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {        
        $this->_driver  = new \Behat\Mink\Driver\Selenium2Driver('firefox');
        self::$_session = new \Behat\Mink\Session($this->_driver);
        
        self::$_session->start();        
        self::$_session->visit($this->_url);
        
        $this->_page = self::$_session->getPage();   
        
        parent::__construct();
    }   
    
    /**
     * @AfterSuite
     */
    public static function closeSession()
    {
        if (self::$_session)
        {
            self::$_session->stop();
        }        
    }
    
    /**
     * @Given ezen az oldalon vagyok :path
     */
    public function ezenAzOldalonVagyok($path)
    {
        self::$_session->visit($this->_url . $path);
    }

    /**
     * @Given rákattintok a :id menüpontra
     */
    public function rakattintokAMenupontra($id)
    {   
        $menu = $this->_page->findById($id);
        $menu->click();
    }

    /**
     * @Given kitöltöm az :id mezőt a :value értékkel
     */
    public function kitoltomAzMezotAErtekkel($id, $value)
    {
        $email = $this->_page->findById($id);        
        $email->setValue($value);
    }
    
    /**
     * @Given beküldöm a :id űrlapot
     */
    public function bekuldomAUrlapot($id)
    {
        $form = $this->_page->findById($id);  
        $form->submit();
    }
    
    /**
     * @Then látnom kell a címsorban a :text szöveget
     */
    public function latnomKellACimsorbanASzoveget($text)
    {
        $headers = $this->_page->findAll('css', 'h1');
        $values = [];
            
        foreach ($headers as $header)
        {
            $values[] = $header->getText();
        }
            
        $this->assertTrue(in_array($text, $values));
    }    
    
    /**
     * @Then látnom kell a :text hibaüzenetet
     */
    public function latnomKellAHibauzenetet($text)
    {
        $label = $this->_page->find('css', '.error-label');
        
        $this->assertEquals($text, $label->getText());
    }
    
    /**
     * @Then megjelenik a :id landing oldal
     */
    public function megjelenikALandingOldal($id)
    {        
        $landing = $this->_page->find('css', 'div#' . $id . '.landing-page');
        
        $this->assertTrue($landing->isVisible());
    }    

    /**
     * @Given megnyomom a :arg1 gombot
     */
    public function megnyomomAGombot($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then látom kell a :text szöveget
     */
    public function latomKellASzoveget($text)
    {
        
    }
    
   /**
    * 
    * Lefuttatja a kapott js kódot
    * 
    * @param string $script         JS kod
    * @param bool $return           Kell -e visszateresi ertek
    * @param mixed $expectedValue   Elvart visszateresi ertek
    * 
    * @return string $value     JS -bol vissza adott ertek
    */
   public function executeScript($script, $return = false, $expectedValue = null)
   {
       if (!$return)
       {
           self::$_session->executeScript($script);
       }
       else
       {
           $temp = trim($script);
           if (substr($temp, 0, 6) != 'return')
           {
               $script = 'return ' . $script;
           }          
           
           $value = self::$_session->evaluateScript($script);
           
           if ($expectedValue && $value != $expectedValue)
           {
                throw new Exception('Excepted value "' . $value . '" is not equal to return value "' . $value . '"');
           }
           
           return $value;
       }
   }
}

