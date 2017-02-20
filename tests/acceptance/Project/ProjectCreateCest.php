<?php


use Faker\Factory;
use Faker\Generator;

class ProjectCreateCest
{
    /**
     * @var Generator
     */
    private $_faker = null;

    /**
     * @var Entity_User_Employer
     */
    private $_employer = null;

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
            'short_description' => $this->_faker->paragraph(),
            'phonenumber' => $this->_faker->phoneNumber,
            'address_city' => $this->_faker->city,
            'address_postal_code' => 1010
        ];

        $entity = new Entity_User_Employer();
        $this->_employer = $entity->submitUser($data);

        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', $this->_employer->getEmail());
        $I->fillField('password', $this->_rawPassword);

        // Belepes
        $I->click('.btn');

        $I->click('#nav-toggle');
        $I->click('#new-project-anchor');

        $I->seeInCurrentUrl('uj-szabaduszo-projekt');
        $I->see('Új Szabadúszó projekt');
    }

    public function _after()
    {
        $this->_employer->delete();
    }

    public function testCreateProjectWithInvalidData(\AcceptanceTester $I)
    {
        $I->wantTo('projekt letrehozas helytelen adatokkal');
        $I->click('.btn');

        $I->seeInCurrentUrl('uj-szabaduszo-projekt');
        $I->see('Új Szabadúszó projekt');
    }

    public function testCreateProjectWithValidData(\AcceptanceTester $I)
    {
        $I->wantTo('projekt letrehozas helyes adatokkal');

        $shortDescription = $this->_faker->paragraph;
        $longDescription = $this->_faker->paragraph(6);

        $I->selectOption('industries[]', 'Informatika');
        $I->fillField('name', 'Teszt projekt');
        $I->fillField('short_description', $shortDescription);
        $I->fillField('long_description', $longDescription);
        $I->fillField('salary_low', '2000');
        $I->fillField('salary_high', '4000');

        $I->click('.btn');

        $I->seeInCurrentUrl('szabaduszo-projekt/teszt-projekt');
        $I->see('Teszt projekt');
        $I->see('Megbízó: ' . $this->_employer->getName());
        $I->see('Informatika', '.project-profession');
        $I->see('2 000 - 4 000 Ft /óra', '.project-details-pay-paragraph');
        $I->see($this->_employer->getPhonenumber());
        $I->see($longDescription, '.project-details-long-description');
        $I->see($shortDescription, '.project-profile-short');
    }
}