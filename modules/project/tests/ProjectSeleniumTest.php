<?php

class ProjectSeleniumTest extends PHPUnit_Framework_TestCase
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
     * Projekt letrehozasa valid adatokkal
     * 
     * @group selenium
     */
    public function testCreateValid()
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
        
        // Projekt
        $newProject = $this->webDriver->findElement(WebDriverBy::id('new-project-anchor'));
        $newProject->click();       
        
        $this->assertContains('Szabaduszok.com - Új Szabadúszó projekt', $this->webDriver->getTitle());
        
        // Iparagak
        
        $industry = $this->webDriver->findElement(WebDriverBy::cssSelector('select#industries + span.select2 input.select2-search__field'));
        $industry->click();
        
        $industryItem1 = $this->webDriver->findElement(WebDriverBy::cssSelector('ul#select2-industries-results li.select2-results__option:nth-child(1)'));
        $industryItem1->click();
        
        $industry->click();
        
        $industryItem2 = $this->webDriver->findElement(WebDriverBy::cssSelector('ul#select2-industries-results li.select2-results__option:nth-child(2)'));
        $industryItem2->click();
        
        // Szakteruletek
        
        $profession = $this->webDriver->findElement(WebDriverBy::cssSelector('select#professions + span.select2 input.select2-search__field'));               
        $profession->click();
        
        $this->webDriver->getKeyboard()->sendKeys('profession1');
        $this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);   
        
        // Megvarja, amig betolti az autocomplete a professionoket
        /*$this->webDriver->wait(10, 500)->until(
            WebDriverExpectedCondition::not(
                WebDriverExpectedCondition::textToBePresentInElement(
                    WebDriverBy::cssSelector('ul#select2-professions-results li.select2-results__option:nth-child(1)'), 
                    'Searching...'
                )
            )
        );                
        
        $professionItem1 = $this->webDriver->findElement(WebDriverBy::cssSelector('ul#select2-professions-results li.select2-results__option:nth-child(1)'));
        var_dump($professionItem1->getText());
        $professionItem2 = $this->webDriver->findElement(WebDriverBy::cssSelector('ul#select2-professions-results li.select2-results__option:nth-child(2)'));
        var_dump($professionItem2->getText());        
        $professionItem3 = $this->webDriver->findElement(WebDriverBy::cssSelector('ul#select2-professions-results li.select2-results__option:nth-child(3)'));
        var_dump($professionItem3->getText());*/
        
        
        exit;
        
        $professionItem1->click();
        
        $profession->click();
        
        $this->webDriver->getKeyboard()->sendKeys('new profession');
        
        // Megvarja, amig betolti az autocomplete a professionoket
        $this->webDriver->wait(10, 500)->until(
            WebDriverExpectedCondition::not(
                WebDriverExpectedCondition::textToBePresentInElement(
                    WebDriverBy::cssSelector('ul#select2-professions-results li.select2-results__option:nth-child(1)'), 
                    'Searching...'
                )
            )
        );        
        
        $this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);   
        
        exit;
        
        // Kepessegek
        
        $skill = $this->webDriver->findElement(WebDriverBy::cssSelector('select#skills + span.select2 input.select2-search__field'));               
        $skill->click();
        
        $this->webDriver->getKeyboard()->sendKeys('skill');
        
        // Megvarja, amig betolti az autocomplete a professionoket
        $this->webDriver->wait(10, 500)->until(
            WebDriverExpectedCondition::not(
                WebDriverExpectedCondition::textToBePresentInElement(
                    WebDriverBy::cssSelector('ul#select2-skills-results li.select2-results__option:nth-child(1)'), 
                    'Gépelj be legalább 1 karaktert'
                )
            )
        );        
        
        $skillItem1 = $this->webDriver->findElement(WebDriverBy::cssSelector('ul#select2-professions-results li.select2-results__option:nth-child(2)'));
        $skillItem1->click();
        
        $skill->click();
        
        $this->webDriver->getKeyboard()->sendKeys('new skill');
        $this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);     
        
        exit;
    }
}