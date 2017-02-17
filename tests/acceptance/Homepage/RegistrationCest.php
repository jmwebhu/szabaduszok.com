<?php


use Faker\Factory;
use Faker\Generator;

class RegistrationCest
{
    /**
     * @var Generator
     */
    private $_faker = null;

    private $_data = [];

    public function _before()
    {
        $this->_faker = Factory::create();
        $first = $this->_faker->firstName;
        $last = $this->_faker->lastName;

        $slug = strtolower($last) . '-' . strtolower($first);
        $this->_data = [
            'first' => $first,
            'last' => $last,
            'slug' => $slug,
            'description' => $this->_faker->paragraph(),
            'city' => $this->_faker->city,
            'email' => $slug . '@szabaduszok.com',
            'password' => $this->_faker->password()
        ];
    }

    public function testValidRegistrationAsFreelancer(\AcceptanceTester $I)
    {
        $I->wantTo('szabaduszo regisztracio helyes adatokkal');
        $I->amOnPage('/szabaduszo-regisztracio');

        $hourlyWage = $this->_faker->randomFloat(0, 1200, 5000);

        $I->fillField('lastname', $this->_data['last']);
        $I->fillField('firstname', $this->_data['first']);
        $I->fillField('email', $this->_data['email']);
        $I->click('.next');

        $I->selectOption('industries[]', 'Informatika');
        $I->selectOption('industries[]', 'Marketing');
        $I->fillField('min_net_hourly_wage', $hourlyWage);
        $I->fillField('short_description', $this->_data['description']);
        $I->click('.next');

        $I->click('.next');
        $I->fillField('address_city', $this->_data['city']);

        $I->click('.next');

        $I->fillField('password', $this->_data['password']);
        $I->fillField('password_confirm', $this->_data['password']);
        $I->click('.btn-lime-green');

        $I->seeInCurrentUrl('/szabaduszo/' . $this->_data['slug']);
        $I->see($this->_data['last'] . ' ' . $this->_data['first']);
        $I->see('Szabadúszó profil');
        $I->see($this->_data['city'], '.details-container');
        $I->see(number_format($hourlyWage, 0, '.', ' ') . ' Ft /óra', '.details-container');
        $I->see($this->_data['description'], '.bottom-container');
        $I->see('Marketing', '.tag');
    }

    public function testInvalidRegistrationAsFreelancer(\AcceptanceTester $I)
    {
        $I->wantTo('szabaduszo regisztracio helytelen adatokkal');
        $I->amOnPage('/szabaduszo-regisztracio');

        $I->click('.next');
        $I->click('.next');
        $I->click('.next');
        $I->click('.next');
        $I->click('.btn-lime-green');

        $I->see('Kérlek javítsd ki az alábbi hibákat', '.panel-heading');
        $I->see('A vezetéknevet kérlek ne hagyd üresen', '.alert-danger');
        $I->see('A keresztnevet kérlek ne hagyd üresen', '.alert-danger');
        $I->see('Az e-mailt kérlek ne hagyd üresen', '.alert-danger');
        $I->see('A minimum nettó órabért kérlek ne hagyd üresen', '.alert-danger');
        $I->see('Jelszó mező nem lehet üres', '.alert-danger');
    }

    public function testValidRegistrationAsEmployer(\AcceptanceTester $I)
    {
        $I->wantTo('megbizo regisztracio helyes adatokkal');
        $I->amOnPage('/megbizo-regisztracio');

        $company = $this->_faker->company;

        $postal = $this->_faker->randomFloat(0, 1000, 9999);
        $phone = $this->_faker->phoneNumber;

        $I->checkOption('is_company');
        $I->fillField('company_name', $company);
        $I->fillField('lastname', $this->_data['last']);
        $I->fillField('firstname', $this->_data['first']);
        $I->fillField('email', $this->_data['email']);
        $I->fillField('phonenumber', $phone);
        $I->click('.next');

        $I->selectOption('industries[]', 'Informatika');
        $I->fillField('short_description', $this->_data['description']);
        $I->click('.next');

        $I->click('.next');
        $I->fillField('address_city', $this->_data['city']);
        $I->fillField('address_postal_code', $postal);

        $I->click('.next');

        $I->fillField('password', $this->_data['password']);
        $I->fillField('password_confirm', $this->_data['password']);
        $I->click('.btn-lime-green');

        $I->seeInCurrentUrl('/megbizo/' . $this->_data['slug']);
        $I->see($this->_data['last'] . ' ' . $this->_data['first']);
        $I->see('Megbízó profil');
        $I->see($company, '.details-container');
        $I->see($this->_data['city'], '.details-container');
        $I->see($this->_data['description'], '.bottom-container');
        $I->see('Informatika', '.tag');
    }

    public function testInvalidRegistrationAsEmployer(\AcceptanceTester $I)
    {
        $I->wantTo('megbizo regisztracio helytelen adatokkal');
        $I->amOnPage('/megbizo-regisztracio');

        $I->click('.next');
        $I->click('.next');
        $I->click('.next');
        $I->click('.next');
        $I->click('.btn-lime-green');

        $I->see('Kérlek javítsd ki az alábbi hibákat', '.panel-heading');
        $I->see('A vezetéknevet kérlek ne hagyd üresen', '.alert-danger');
        $I->see('A keresztnevet kérlek ne hagyd üresen', '.alert-danger');
        $I->see('Az e-mailt kérlek ne hagyd üresen', '.alert-danger');
        $I->see('Az irányítószámot kérlek ne hagyd üresen', '.alert-danger');
        $I->see('A várost kérlek ne hagyd üresen', '.alert-danger');
        $I->see('A telefonszám mező nem lehet üres', '.alert-danger');
        $I->see('Jelszó mező nem lehet üres', '.alert-danger');
    }
}