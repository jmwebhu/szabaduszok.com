<?php


class LoginCest
{
    public function testLoginWithValidDataFreelancer(\AcceptanceTester $I)
    {
        $I->wantTo('belepes helyes adatokkal szabaduszokent');
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', 'm4rt1n.j00@gmail.com');
        $I->fillField('password', 'Deth4Life01');
        $I->click('.btn');
        $I->seeInCurrentUrl('szabaduszo/joo-martin');
        $I->see('Joó Martin');
        $I->see('Szabadúszó profil');
    }

    public function testLoginWithValidDataEmployeer(\AcceptanceTester $I)
    {
        $I->wantTo('belepes helyes adatokkal megbizokent');
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', 'joomartin@jmweb.hu');
        $I->fillField('password', 'Deth4Life01');
        $I->click('.btn');
        $I->seeInCurrentUrl('megbizo/joo-martin');
        $I->see('Joó Martin');
        $I->see('Megbízó profil');
    }

    public function testLoginWithInvalidEmail(\AcceptanceTester $I)
    {
        $I->wantTo('belepes helytelen e-mail cimmel');
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', 'm4rt1n12.j00@gmail.com');
        $I->fillField('password', 'Deth4Life01');
        $I->click('.btn');
        $I->seeInCurrentUrl('szabaduszok-belepes');
        $I->see('Hibás e-mail vagy jelszó. Kérjük próbáld meg újra!', '.error-label');
    }

    public function testLoginWithInvalidPassword(\AcceptanceTester $I)
    {
        $I->wantTo('belepes helytelen jelszoval');
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', 'm4rt1n.j00@gmail.com');
        $I->fillField('password', 'Dethew4Lasdfife01');
        $I->click('.btn');
        $I->seeInCurrentUrl('szabaduszok-belepes');
        $I->see('Hibás e-mail vagy jelszó. Kérjük próbáld meg újra!', '.error-label');
    }

    public function testLoginWithInvalidEmailAndPassword(\AcceptanceTester $I)
    {
        $I->wantTo('belepes helytelen email cimmel es jelszoval');
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', 'm4rtasdfas1n.j00@gmail.com');
        $I->fillField('password', 'Dethew4Lasdasdffife01');
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
        $I->fillField('email', 'm4rt1n.j00@gmail.com');
        $I->click('.btn');
        $I->seeInCurrentUrl('/szabaduszok-belepes');
        $I->see('Szabaduszok.com Belépés');
    }
}