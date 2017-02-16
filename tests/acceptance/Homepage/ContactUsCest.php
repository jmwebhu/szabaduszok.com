<?php


class ContactUsCest
{
    private $_faker = null;

    public function _before(\AcceptanceTester $I)
    {
    }

    public function testSendInvalidForm(\AcceptanceTester $I)
    {
/*        $I->wantTo('irj nekunk hibas form');
        $I->amOnPage('/szabaduszok-irj-nekunk');
        $I->fillField('Név*', $this->_faker->name);
        $I->fillField('Email*', $this->_faker->email);
        $I->click('Elküld');
        $I->see('Kérlek minden mezőt tölts ki', '.alert-danger');*/
    }

    public function testSendValidForm(\AcceptanceTester $I)
    {
/*        $I->wantTo('irj nekunk helyes form');
        $I->amOnPage('/szabaduszok-irj-nekunk');
        $I->fillField('Név*', $this->_faker->name);
        $I->fillField('Email*', $this->_faker->email);
        $I->fillField('Üzenet*', $this->_faker->paragraph);
        $I->click('Elküld');
        $I->see('Köszönjük, hogy írtál nekünk, a lehető leghamarabb válaszolni fogunk!', '.alert-success');*/
    }
}