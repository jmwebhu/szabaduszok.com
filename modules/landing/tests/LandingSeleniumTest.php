<?php

class LandingSeleniumTest extends PHPUnit_Framework_TestCase
{
     /**
     * @var \RemoteWebDriver
     */
    protected $webDriver;
    protected $url = 'http://127.0.0.1/v21.szabaduszok.com';
    
    protected $landingPages = [
        'startup'       => ['landingHeader' => 'Startupod', 'regTitle' => 'Megbízó'],
        'employer'      => ['landingHeader' => 'Ötleted', 'regTitle' => 'Megbízó'],
        'freelancer'    => ['landingHeader' => 'Karriered', 'regTitle' => 'Szabadúszó']
    ];

    public function setUp()
    {
        $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => 'firefox');
        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);                
    }
    
    public function tearDown()
    {
        $this->webDriver->quit();
    }        
    
    /**
     * Landing oldalak megnyitasa
     * 
     * @group selenium
     */
    public function testLandingPageOpen()
    {
        foreach ($this->landingPages as $id => $item)
        {
            $this->webDriver->get($this->url . '?landing=' . $id);  
          
            $h1 = $this->webDriver->findElement(           
                WebDriverBy::cssSelector('div#' . $id . ' h1.main-header')
            );
            
            $this->assertContains('Szabaduszok.com', $this->webDriver->getTitle());
            $this->assertContains($item['landingHeader'], $h1->getText());        
        }
    }
    
    /**
     * Landing oldalak megnyitasa, e-mail kitoltese
     * 
     * @group selenium
     */
    public function testLandingPageOpenConvert()
    {
        foreach ($this->landingPages as $id => $item)
        {
            $this->webDriver->get($this->url . '?landing=' . $id);  
          
            $email = $this->webDriver->findElement(WebDriverBy::cssSelector('div#' . $id . ' input.landing-email'));
            $email->click();
            
            $this->webDriver->getKeyboard()->sendKeys('joomartin@jmweb.hu');
            $this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER); 
            
            $emailReg = $this->webDriver->findElement(WebDriverBy::id('email'));
            
            $this->assertEquals('joomartin@jmweb.hu', $emailReg->getAttribute("value"));
            $this->assertEquals('Szabaduszok.com - ' . $item['regTitle'] . ' Regisztráció', $this->webDriver->getTitle());   
        }               
    }        
}