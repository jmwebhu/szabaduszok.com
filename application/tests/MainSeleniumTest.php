<?php

class MainSeleniumTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \RemoteWebDriver
     */
    protected $webDriver;
    protected $url = 'http://127.0.0.1/v21.szabaduszok.com';

    public function setUp()
    {
        $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => 'firefox');
        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);                
    }
    
    public function tearDown()
    {
        $this->webDriver->quit();
    }        
    
    public function testHome()
    {
        $this->webDriver->get($this->url);              
        $this->assertContains('Szabaduszok.com', $this->webDriver->getTitle());
    }
}