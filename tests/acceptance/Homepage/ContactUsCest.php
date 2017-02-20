<?php

use Faker\Factory;
use Faker\Generator;

class ContactUsCest
{
    /**
     * @var Generator
     */
    private $_faker = null;

    public function _before()
    {
        $this->_faker = Factory::create();
    }

    public function testSendInvalidForm(\AcceptanceTester $I)
    {
        $I->wantTo('irj nekunk hibas form');
        $I->amOnPage('/szabaduszok-irj-nekunk');
        $I->fillField('name', $this->_faker->name);
        $I->fillField('email', $this->_faker->email);
        $I->click('Elküld');
        $I->see('Kérlek minden mezőt tölts ki', '.alert-danger');
    }

    public function testSendValidForm(\AcceptanceTester $I)
    {
        $I->wantTo('irj nekunk helyes form');
        $I->amOnPage('/szabaduszok-irj-nekunk');
        $I->fillField('name', $this->_faker->name);
        $I->fillField('email', $this->_faker->email);
        $I->fillField('message', $this->_faker->paragraph);
        $I->click('Elküld');
        $I->see('Köszönjük, hogy írtál nekünk, a lehető leghamarabb válaszolni fogunk!', '.alert-success');
    }
}