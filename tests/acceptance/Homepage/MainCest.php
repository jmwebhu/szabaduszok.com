<?php

class MainCest
{
    public function testMainPage(\AcceptanceTester $I)
    {
        $I->wantTo('fooldal alap teszt');
        $I->amOnPage('/');
        $I->see('A magyar freelancer platform');
        $I->see('Szabadúszók');
        $I->see('Megbízók');
        $I->see('Projektek');
    }

    public function testMainPageWithFreelancerLandingPage(\AcceptanceTester $I)
    {
        $I->wantTo('fooldal szabaduszo landing oldal');
        $I->amOnPage('/?landing=freelancer');
        $I->see('Vigyázz, Kész, Kilövés!');
        $I->see('Válogass a projektek között');
        $I->see('REGISZTRÁCIÓ');
    }

    public function testMainPageWithEmployeerLandingPage(\AcceptanceTester $I)
    {
        $I->wantTo('fooldal megbizo landing oldal');
        $I->amOnPage('/?landing=employeer');
        $I->see('Pörgesd Fel az Ötleted Megvalósítását Még Ma!');
        $I->see('Korlátozott ideig teljesen ingyen');
        $I->see('TOVÁBB');
    }

    public function testNavigateToInterestedIn(\AcceptanceTester $I)
    {
        $I->wantTo('navigalas erdekel oldalra');
        $I->amOnPage('/');
        $I->click('Érdekel');
        $I->see('Csatlakozz a Szabaduszok.com közösségéhez!');
        $I->see('Szabadúszó vagyok, csatlakozom!');
        $I->see('Megbízó vagyok, csatlakozom!');
    }

    public function testNavigateToHowItWorks(\AcceptanceTester $I)
    {
        $I->wantTo('navigalas hogyan mukodik oldalra');
        $I->amOnPage('/');
        $I->click('Hogyan működik?');
        $I->see('Hogyan működik a Szabaduszok.com?');
        $I->see('A platform főbb részei');
    }

    public function testNavigateToContactUs(\AcceptanceTester $I)
    {
        $I->wantTo('navigalas irj nekunk oldalra');
        $I->amOnPage('/');
        $I->click('Írj nekünk!');
        $I->see('Szabaduszok.com - Írj nekünk!');
        $I->see('Elküld');
    }

    public function testNavigateToTermsOfUse(\AcceptanceTester $I)
    {
        $I->wantTo('navigalas felhasznalasi feltetelek oldalra');
        $I->amOnPage('/');
        $I->click('Felhasználási feltételek');
        $I->see('Szabaduszok.com - Felhasználási feltételek');
        $I->see('A felhasználási feltételek tartalma');
    }

    public function testNavigateToPrivacy(\AcceptanceTester $I)
    {
        $I->wantTo('navigalas adatvedelem oldalra');
        $I->amOnPage('/');
        $I->click('Adatvédelem');
        $I->see('Szabaduszok.com - Adatvédelem');
        $I->see('A szabályzat célja');
    }

    public function testNavigateToLogin(\AcceptanceTester $I)
    {
        $I->wantTo('navigalas belepes oldalra');
        $I->amOnPage('/');
        $I->click('#nav-toggle');
        $I->click('#login-anchor');
        $I->seeInCurrentUrl('szabaduszok-belepes');
        $I->see('Szabaduszok.com Belépés');
        $I->see('Belépés');
    }

    public function testNavigateToRegistrationAsFreelancer(\AcceptanceTester $I)
    {
        $I->wantTo('navigalas szabaduszo regisztracio oldalra');
        $I->amOnPage('/');
        $I->click('#nav-toggle');
        $I->click('#interested-anchor');
        $I->click('Szabadúszó vagyok, csatlakozom!');

        $I->seeInCurrentUrl('/szabaduszo-regisztracio');
        $I->see('Szabadúszó Regisztráció');
    }

    public function testNavigateToRegistrationAsEmployeer(\AcceptanceTester $I)
    {
        $I->wantTo('navigalas megbizo regisztracio oldalra');
        $I->amOnPage('/');
        $I->click('#nav-toggle');
        $I->click('#interested-anchor');
        $I->click('Megbízó vagyok, csatlakozom!');

        $I->seeInCurrentUrl('/megbizo-regisztracio');
        $I->see('Megbízó Regisztráció');
    }
}