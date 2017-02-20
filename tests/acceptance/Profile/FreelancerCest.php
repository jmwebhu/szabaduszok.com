<?php


use Faker\Factory;
use Faker\Generator;

class FreelancerCest
{
    /**
     * @var Generator
     */
    private $_faker = null;

    /**
     * @var Entity_User
     */
    private $_freelancer = null;

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
        ];

        $entity = new Entity_User_Freelancer;
        $this->_freelancer = $entity->submitUser($data);

        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', $this->_freelancer->getEmail());
        $I->fillField('password', $this->_rawPassword);

        // Belepes
        $I->click('.btn');

        // Szerkesztes
        $I->click('.btn-ultramarine-blue');
    }

    public function _after()
    {
        $this->_freelancer->delete();
    }

    public function testTabsAndNavigation(\AcceptanceTester $I)
    {
        $I->wantTo('profil szerkesztes tabok es navigalas');

        $I->seeInCurrentUrl('szabaduszo-profil-szerkesztes/' . $this->_freelancer->getSlug());
        $I->see('Profil szerkesztése: ' . $this->_freelancer->getName());

        $I->see('Személyes profil', 'li');
        $I->see('Szakmai profil', 'li');
        $I->see('Külső profilok', 'li');
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

        // Kulso profilok
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
        $I->see('Rögzít');
        $I->see('Vissza');

        $I->click('Rögzít');

        $I->seeInCurrentUrl('szabaduszo/' . $this->_freelancer->getSlug());
        $I->see($this->_freelancer->getName());
        $I->see('Szabadúszó profil');
    }
}