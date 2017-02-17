<?php


use Faker\Factory;
use Faker\Generator;

class RegistrationCest
{
    /**
     * @var Generator
     */
    private $_faker = null;

    public function _before()
    {
        $this->_faker = Factory::create();
    }

    public function testValidRegistrationAsFreelancer(\AcceptanceTester $I)
    {
        $I->wantTo('szabaduszo regisztracio helyes adatokkal');
        $I->amOnPage('/szabaduszo-regisztracio');

        $last = $this->_faker->lastName;
        $first = $this->_faker->firstName;

        $I->fillField('lastname', $last);
        $I->fillField('firstname', $first);
        $I->fillField('email', $this->_faker->email);
        $I->click('.next');

        $I->selectOption('industries[]', 'Informatika');
        $I->selectOption('industries[]', 'Marketing');
        $I->fillField('min_net_hourly_wage', $this->_faker->randomFloat(2, 1200, 5000));
        $I->fillField('short_description', $this->_faker->paragraph());
        $I->click('.next');

        $I->click('.next');
        $I->click('.next');

        $password = $this->_faker->password();
        $I->fillField('password', $password);
        $I->fillField('password_confirm', $password);
        $I->click('.btn-lime-green');

        $slug = strtolower($last) . '-' . strtolower($first);
        $I->seeInCurrentUrl('/szabaduszo/' . $slug);
        $I->see($last . ' ' . $first);
        $I->see('Szabadúszó profil');
    }
}