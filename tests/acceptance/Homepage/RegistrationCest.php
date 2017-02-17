<?php


class RegistrationCest
{
    public function testInvalidRegistrationAsFreelancer(\AcceptanceTester $I)
    {
        $I->wantTo('szabaduszo regisztracio hibas adatokkal');
        $I->amOnPage('/szabaduszo-regisztracio');
    }
}