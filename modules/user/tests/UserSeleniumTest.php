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
    
    /**
     * Belepes valid adatokkal
     * 
     * @group selenium
     */
    public function testLoginValid()
    {
        $this->webDriver->get($this->url);
        
        $login = $this->webDriver->findElement(WebDriverBy::id('login-anchor'));
        $login->click();
        
        $email = $this->webDriver->findElement(WebDriverBy::id('email'));
        $email->click();
        $this->webDriver->getKeyboard()->sendKeys('joomartin@jmweb.hu');
        
        $password = $this->webDriver->findElement(WebDriverBy::id('password'));
        $password->click();        
        $this->webDriver->getKeyboard()->sendKeys('Deth4Life01');
        
        $this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER); 
        
        $h1 = $this->webDriver->findElement(           
            WebDriverBy::cssSelector('div.row h1.mg-md')
        );
        
        $this->assertEquals('Joó Martin', $h1->getText());
    }
    
    /**
     * Belepes invalid adatokkal
     * 
     * @group selenium
     */
    public function testLoginInvalid()
    {
        $this->webDriver->get($this->url);             
        
        $login = $this->webDriver->findElement(WebDriverBy::id('login-anchor'));
        $login->click();
        
        $email = $this->webDriver->findElement(WebDriverBy::id('email'));
        $email->click();
        $this->webDriver->getKeyboard()->sendKeys('joomartin111@jmweb.hu');
        
        $password = $this->webDriver->findElement(WebDriverBy::id('password'));
        $password->click();        
        $this->webDriver->getKeyboard()->sendKeys('Deth4Life0111');
        
        $this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER); 
        
        $span = $this->webDriver->findElement(           
            WebDriverBy::cssSelector('span.error-label')
        );
        
        $this->assertEquals('Hibás e-mail vagy jelszó. Kérjük próbáld meg újra!', $span->getText());
    }
}