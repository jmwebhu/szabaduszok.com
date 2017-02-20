<?php


use Faker\Factory;
use Faker\Generator;

class EmployerCest
{
    /**
     * @var Generator
     */
    private $_faker = null;

    /**
     * @var Entity_User
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

        // Szerkesztes
        $I->click('.btn-ultramarine-blue');
    }

    public function _after()
    {
        $this->_employer->delete();
    }

    public function testTabsAndNavigation(\AcceptanceTester $I)
    {
        $I->wantTo('profil szerkesztes tabok es navigalas');

        $I->seeInCurrentUrl('megbizo-profil-szerkesztes/' . $this->_employer->getSlug());
        $I->see('Profil szerkesztése: ' . $this->_employer->getName());

        $I->see('Személyes profil', 'li');
        $I->see('Szakmai profil', 'li');
        $I->see('Cím', 'li');
        $I->see('Jelszó', 'li');

        // Szemelyes profil
        $I->see('Tovább');
        $I->see('Rögzít');

        // Szakmai profil
        $I->click('.next');
        $I->see('Tovább');
        $I->see('Rögzít');
        $I->see('Vissza');

        // Cim
        $I->click('.next');
        $I->see('Tovább');
        $I->see('Rögzít');
        $I->see('Vissza');

        // Jelszo
        $I->click('.next');
        $I->see('Rögzít');
        $I->see('Vissza');

        $I->click('Rögzít');

        $I->seeInCurrentUrl('megbizo/' . $this->_employer->getSlug());
        $I->see($this->_employer->getName());
        $I->see('Megbízó profil');
    }

}