<?php


use Faker\Factory;
use Faker\Generator;

class LoginCest
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
     * @var Entity_User
     */
    private $_employer = null;

    /**
     * @var string
     */
    private $_rawPasswordFreelancer;

    /**
     * @var string
     */
    private $_rawPasswordEmployer;

    public function _before()
    {
        $this->_faker = Factory::create();

        $lastName = $this->_faker->lastName;
        $firstName = $this->_faker->firstName;
        $this->_rawPasswordFreelancer = $this->_faker->password();

        $data = [
            'lastname' => $lastName,
            'firstname' => $firstName,
            'email' => URL::slug($lastName . ' ' . $firstName) . '@szabaduszok.com',
            'password' => $this->_rawPasswordFreelancer,
            'password_confirm' => $this->_rawPasswordFreelancer,
            'min_net_hourly_wage' => 3000,
            'short_description' => $this->_faker->paragraph(),
        ];

        $entity = new Entity_User_Freelancer;
        $this->_freelancer = $entity->submitUser($data);

        $lastName = $this->_faker->lastName;
        $firstName = $this->_faker->firstName;

        $this->_rawPasswordEmployer = $this->_faker->password();

        $data = [
            'lastname' => $lastName,
            'firstname' => $firstName,
            'email' => URL::slug($lastName . ' ' . $firstName) . '@szabaduszok.com',
            'password' => $this->_rawPasswordEmployer,
            'password_confirm' => $this->_rawPasswordEmployer,
            'short_description' => $this->_faker->paragraph(),
            'phonenumber' => $this->_faker->phoneNumber,
            'address_city' => $this->_faker->city,
            'address_postal_code' => 1010
        ];

        $entity = new Entity_User_Employer;
        $this->_employer = $entity->submitUser($data);
    }

    public function _after()
    {
        $this->_freelancer->delete();
        $this->_employer->delete();
    }

    public function testLoginWithValidDataFreelancer(\AcceptanceTester $I)
    {
        $I->wantTo('belepes helyes adatokkal szabaduszokent');
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', $this->_freelancer->getEmail());
        $I->fillField('password', $this->_rawPasswordFreelancer);
        $I->click('.btn');
        $I->seeInCurrentUrl('szabaduszo/' . $this->_freelancer->getSlug());
        $I->see($this->_freelancer->getName());
        $I->see('Szabadúszó profil');
    }

    public function testLoginWithValidDataEmployer(\AcceptanceTester $I)
    {
        $I->wantTo('belepes helyes adatokkal megbizokent');
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', $this->_employer->getEmail());
        $I->fillField('password', $this->_rawPasswordEmployer);
        $I->click('.btn');
        $I->seeInCurrentUrl('megbizo/' . $this->_employer->getSlug());
        $I->see($this->_employer->getName());
        $I->see('Megbízó profil');
    }

    public function testLoginWithInvalidEmail(\AcceptanceTester $I)
    {
        $I->wantTo('belepes helytelen e-mail cimmel');
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', 'm4rt1n12.j00@gmail.com');
        $I->fillField('password', $this->_rawPasswordFreelancer);
        $I->click('.btn');
        $I->seeInCurrentUrl('szabaduszok-belepes');
        $I->see('Hibás e-mail vagy jelszó. Kérjük próbáld meg újra!', '.error-label');
    }

    public function testLoginWithInvalidPassword(\AcceptanceTester $I)
    {
        $I->wantTo('belepes helytelen jelszoval');
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', 'm4rt1n.j00@gmail.com');
        $I->fillField('password', 'sdfjshdfPass222');
        $I->click('.btn');
        $I->seeInCurrentUrl('szabaduszok-belepes');
        $I->see('Hibás e-mail vagy jelszó. Kérjük próbáld meg újra!', '.error-label');
    }

    public function testLoginWithInvalidEmailAndPassword(\AcceptanceTester $I)
    {
        $I->wantTo('belepes helytelen email cimmel es jelszoval');
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', 'm4rtasdfas1n.j00@gmail.com');
        $I->fillField('password', 'invssssfOPassWo');
        $I->click('.btn');
        $I->seeInCurrentUrl('szabaduszok-belepes');
        $I->see('Hibás e-mail vagy jelszó. Kérjük próbáld meg újra!', '.error-label');
    }
    
    public function testNavigateToPasswordReminderPage(\AcceptanceTester $I)
    {
        $I->wantTo('navigalas elfelejtett jelszo oldalra belepes oldalrol');
        $I->amOnPage('/szabaduszok-belepes');
        $I->click('Elfelejtettem a jelszavam');
        $I->seeInCurrentUrl('szabaduszok-elfelejtett-jelszo');
        $I->see('Elfelejtett jelszó');
        $I->see('Új jelszót kérek');
    }

    public function testNavigateToInterestedInPage(\AcceptanceTester $I)
    {
        $I->wantTo('navigalas erdekel oldalra belepes oldalrol');
        $I->amOnPage('/szabaduszok-belepes');
        $I->click('Kattints ide!');
        $I->seeInCurrentUrl('erdekel');
        $I->see('Csatlakozz a Szabaduszok.com közösségéhez!');
        $I->see('Szabadúszó vagyok, csatlakozom!');
        $I->see('Megbízó vagyok, csatlakozom!');
    }
    
    public function testPasswordReminderWithInvalidEmail(\AcceptanceTester $I)
    {
        $I->wantTo('elfelejtett jelszo helytelen e-mail cimmel');
        $I->amOnPage('/szabaduszok-elfelejtett-jelszo');
        $I->click('.btn');
        $I->see('Sajnáljuk, nincs ilyen e-mail cím. Kérjük próbáld meg egy másikkal.', '.error-label');
    }

    public function testPasswordReminderWithValidEmail(\AcceptanceTester $I)
    {
        $I->wantTo('elfelejtett jelszo helyes e-mail cimmel');
        $I->amOnPage('/szabaduszok-elfelejtett-jelszo');
        $I->fillField('email', $this->_freelancer->getEmail());
        $I->click('.btn');
        $I->seeInCurrentUrl('/szabaduszok-belepes');
        $I->see('Szabaduszok.com Belépés');
    }
}