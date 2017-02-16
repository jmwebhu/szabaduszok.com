<?php


class LoginCest
{
    public function testLoginWithValidData(\AcceptanceTester $I)
    {
        $I->wantTo('belepes helyes adatokkal');
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', 'm4rt1n.j00@gmail.com');
        $I->fillField('password', 'Deth4Life01');
        $I->click('.btn');
        $I->seeInCurrentUrl('szabaduszo/joo-martin');
        $I->see('Joó Martin');
        $I->see('Szabadúszó profil');
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
}