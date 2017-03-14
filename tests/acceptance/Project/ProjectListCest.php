<?php


use Faker\Factory;
use Faker\Generator;

class ProjectListCest
{
    /**
     * @var Generator
     */
    private $_faker = null;

    /**
     * @var Entity_User_Freelancer
     */
    private $_freelancer = null;

    /**
     * @var Entity_User_Employer
     */
    private $_employer = null;

    /**
     * @var Entity_Project
     */
    private $_project = null;

    /**
     * @var string
     */
    private $_rawPassword;

    public function _before(\AcceptanceTester $I)
    {
        $this->_faker = Factory::create();

        $lastName = $this->_faker->lastName;
        $firstName = $this->_faker->firstName;
        $this->_rawPassword = $this->_faker->password();

        $data = [
            'lastname' => $lastName,
            'firstname' => $firstName,
            'email' => URL::slug($lastName . ' ' . $firstName) . '@szabaduszok.com',
            'password' => $this->_rawPassword,
            'password_confirm' => $this->_rawPassword,
            'min_net_hourly_wage' => 3000,
            'short_description' => $this->_faker->paragraph(),
            'industries' => [1]
        ];

        $entity = new Entity_User_Freelancer;
        $this->_freelancer = $entity->submitUser($data);

        $lastName = $this->_faker->lastName;
        $firstName = $this->_faker->firstName;
        $password = $this->_faker->password();

        $data = [
            'lastname' => $lastName,
            'firstname' => $firstName,
            'email' => URL::slug($lastName . ' ' . $firstName) . '@szabaduszok.com',
            'password' => $password,
            'password_confirm' => $password,
            'short_description' => $this->_faker->paragraph(),
            'phonenumber' => $this->_faker->phoneNumber,
            'address_city' => $this->_faker->city,
            'address_postal_code' => 1010
        ];

        $entity = new Entity_User_Employer;
        $this->_employer = $entity->submitUser($data);

        $data = [
            'industries' => [2, 3],
            'name' => 'A marketing projekt',
            'short_description' => 'Rövid leírás a marketing projekthez',
            'long_description' => 'HOsszabb leírása az univerzális marketing projektnek',
            'salary_type' => 1,
            'salary_low' => 1500,
            'salary_high' => 2400,
            'phonenumber' => $this->_employer->getPhonenumber(),
            'email' => $this->_employer->getEmail(),
        ];

        $entity = new Entity_Project;
        $this->_project = $entity->submit($data);

        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', $this->_freelancer->getEmail());
        $I->fillField('password', $this->_rawPassword);

        // Belepes
        $I->click('.btn');

        $I->click('#nav-toggle');
        $I->click('#projects-anchor');
    }

    public function _after()
    {
        $this->_freelancer->delete();
        $this->_employer->delete();
        $this->_project->delete();
    }

    public function testProjectList(\AcceptanceTester $I)
    {
        $I->wantTo('projekt lista alap megjelenes');

        $I->see('A marketing projekt', '.project-title-header');
        $I->see($this->_project->getName(), '.project-title-header');
    }

    public function testSearchInList(\AcceptanceTester $I)
    {
        $I->wantTo('projekt lista kereses');

        $I->selectOption('industries[]', 'Informatika');
        $I->click('#complex-submit');

        $I->dontSee($this->_project->getName());
    }

    public function testSearchInListWithMyProject(\AcceptanceTester $I)
    {
        $I->wantTo('projekt lista kereses');

        $I->selectOption('industries[]', 'Marketing');
        $I->click('#complex-submit');

        $I->see($this->_project->getName());
    }

    public function testClickOnProject(\AcceptanceTester $I)
    {
        $I->wantTo('projektre kattintas');

        $I->click('A marketing projekt');

        $I->seeInCurrentUrl('szabaduszo-projekt/a-marketing-projekt');
        $I->see('A marketing projekt');
    }
}