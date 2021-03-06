<?php


use Faker\Factory;
use Faker\Generator;

class ProjectPartnerCest
{
    /**
     * @var Generator
     */
    private $_faker = null;

    /**
     * @var Entity_User_Freelancer
     */
    private $_freelancer = null;

    /**
     * @var Entity_User_Employer
     */
    private $_employer = null;

    /**
     * @var Entity_Project
     */
    private $_project = null;

    /**
     * @var string
     */
    private $_freelancerRawPassword;

    /**
     * @var string
     */
    private $_employerRawPassword;

    /**
     * @var int
     */
    private $_projectPartnerId;

    public function _before()
    {
        $this->_faker = Factory::create();

        $lastName = $this->_faker->lastName;
        $firstName = $this->_faker->firstName;
        $this->_freelancerRawPassword = $this->_faker->password();

        $data = [
            'lastname'            => $lastName,
            'firstname'           => $firstName,
            'email'               => URL::slug($lastName . ' ' . $firstName) . '@szabaduszok.com',
            'password'            => $this->_freelancerRawPassword,
            'password_confirm'    => $this->_freelancerRawPassword,
            'min_net_hourly_wage' => 3000,
            'short_description'   => $this->_faker->paragraph(),
            'industries'          => [1]
        ];

        $entity = new Entity_User_Freelancer;
        $this->_freelancer = $entity->submitUser($data);

        $lastName = $this->_faker->lastName;
        $firstName = $this->_faker->firstName;
        $this->_employerRawPassword = $this->_faker->password();

        $data = [
            'lastname'            => $lastName,
            'firstname'           => $firstName,
            'email'               => URL::slug($lastName . ' ' . $firstName) . '@szabaduszok.com',
            'password'            => $this->_employerRawPassword,
            'password_confirm'    => $this->_employerRawPassword,
            'short_description'   => $this->_faker->paragraph(),
            'phonenumber'         => $this->_faker->phoneNumber,
            'address_city'        => $this->_faker->city,
            'address_postal_code' => 1010
        ];

        $entity = new Entity_User_Employer;
        $this->_employer = $entity->submitUser($data);

        $data = [
            'industries'        => [2, 3],
            'name'              => 'A marketing projekt',
            'short_description' => 'Rövid leírás a marketing projekthez',
            'long_description'  => 'HOsszabb leírása az univerzális marketing projektnek',
            'salary_type'       => 1,
            'salary_low'        => 1500,
            'salary_high'       => 2400,
            'phonenumber'       => $this->_employer->getPhonenumber(),
            'email'             => $this->_employer->getEmail(),
        ];

        $entity = new Entity_Project;
        $this->_project = $entity->submit($data);

    }

    public function _after()
    {
        $this->_freelancer->delete();
        $this->_employer->delete();
        $this->_project->delete();
    }

    protected function loginAsFreelancer(\AcceptanceTester $I)
    {
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', $this->_freelancer->getEmail());
        $I->fillField('password', $this->_freelancerRawPassword);

        // Belepes
        $I->click('.btn');

        $I->click('#nav-toggle');
        $I->click('#projects-anchor');
        $I->click($this->_project->getName());
    }

    protected function loginAsEmployer(\AcceptanceTester $I)
    {
        $I->amOnPage('/szabaduszok-belepes');
        $I->fillField('email', $this->_employer->getEmail());
        $I->fillField('password', $this->_employerRawPassword);

        // Belepes
        $I->click('.btn');

        $I->click('#nav-toggle');
        $I->click('#projects-anchor');
        $I->click($this->_project->getName());
    }

    public function testFreelancerCanSeeApply(\AcceptanceTester $I)
    {
        $I->wantTo('szabaduszo latja a jelentkezes gombot');
        $this->loginAsFreelancer($I);

        $I->see('Jelentkezés', 'div.panel-body a');
    }

    public function testEmployerCannotSeeApply(\AcceptanceTester $I)
    {
        $I->wantTo('megbizo nem latja a jelentkezes gombot');
        $this->loginAsEmployer($I);

        $I->dontSee('Jelentkezés', 'div.panel-body a');
    }

    public function testFreelancerCanApply(\AcceptanceTester $I)
    {
        $I->wantTo('szabaduszo jelentkezhet a projektre');
        $this->loginAsFreelancer($I);
        $this->apply($I, $this->_freelancer, true);
    }

    public function testEmployerCannotApply(\AcceptanceTester $I)
    {
        $this->loginAsEmployer($I);
        $this->apply($I, $this->_employer, false);
    }

    public function testFreelancerCanUndoApplication(\AcceptanceTester $I)
    {
        $this->loginAsFreelancer($I);
        $this->apply($I, $this->_freelancer, true);
        $this->undoApplication();

        $I->amOnPage('/szabaduszo-projekt/' . $this->_project->getSlug());
        $I->see('Jelentkezés', 'div.panel-body a');
        $I->dontSee('Visszavonás', 'div.panel-body a');
        $I->dontSee($this->_freelancer->getName(), 'div#candidates');
        $I->dontSee($this->_freelancer->getShortDescription(), 'div#candidates');
    }

    public function testEmployerCanApproveApplication(\AcceptanceTester $I)
    {
        // Jelentkezes
        $this->loginAsFreelancer($I);
        $this->apply($I, $this->_freelancer, true);
        $this->logout();

        // Jovahagyas
        $this->loginAsEmployer($I);
        $I->amOnPage('/szabaduszo-projekt/' . $this->_project->getSlug());

        $I->see($this->_freelancer->getName(), 'div#candidates');
        $I->see($this->_freelancer->getShortDescription(), 'div#candidates');
        $I->see('Jóváhagyás', 'div#candidates');

        $this->approveApplication();
        $I->amOnPage('/szabaduszo-projekt/' . $this->_project->getSlug());

        $I->dontSee($this->_freelancer->getName(), 'div#candidates');
        $I->dontSee($this->_freelancer->getShortDescription(), 'div#candidates');
        $I->dontSee('Jóváhagyás', 'div#candidates');

        $I->see($this->_freelancer->getName(), 'div#participants');
        $I->see($this->_freelancer->getShortDescription(), 'div#participants');
        $I->see('Törlés', 'div#participants');
        $this->logout();

        $this->loginAsFreelancer($I);

        $I->amOnPage('/szabaduszo-projekt/' . $this->_project->getSlug());

        $I->dontSee($this->_freelancer->getName(), 'div#candidates');
        $I->dontSee($this->_freelancer->getShortDescription(), 'div#candidates');
        $I->dontSee('Jóváhagyás', 'div#candidates');

        $I->see($this->_freelancer->getName(), 'div#participants');
        $I->see($this->_freelancer->getShortDescription(), 'div#participants');
        $this->logout();

        $this->loginAsEmployer($I);
        $I->amOnPage('/szabaduszo-projekt/' . $this->_project->getSlug());

        $I->see($this->_freelancer->getName(), 'div#participants');
        $I->see($this->_freelancer->getShortDescription(), 'div#participants');
        $I->see('Törlés', 'div#participants');
    }

    public function testEmployerCanRejectApplication(\AcceptanceTester $I)
    {
        $I->wantTo('megbizo elutasithatja a szabaduszo jelentkezeset');

        // Jelentkezes
        $this->loginAsFreelancer($I);
        $this->apply($I, $this->_freelancer, true);
        $this->logout();

        // Elutasitas
        $this->loginAsEmployer($I);
        $I->amOnPage('/szabaduszo-projekt/' . $this->_project->getSlug());

        $I->see($this->_freelancer->getName(), 'div#candidates');
        $I->see($this->_freelancer->getShortDescription(), 'div#candidates');
        $I->see('Jóváhagyás', 'div#candidates');

        $this->rejectApplication();
        $I->amOnPage('/szabaduszo-projekt/' . $this->_project->getSlug());

        $I->dontSee($this->_freelancer->getName(), 'div#candidates');
        $I->dontSee($this->_freelancer->getShortDescription(), 'div#candidates');
        $I->dontSee('Jóváhagyás', 'div#candidates');

        $I->dontSee($this->_freelancer->getName(), 'div#participants');
        $I->dontSee($this->_freelancer->getShortDescription(), 'div#participants');
        $I->dontSee('Törlés', 'div#participants');
        $this->logout();

        $this->loginAsFreelancer($I);

        $I->amOnPage('/szabaduszo-projekt/' . $this->_project->getSlug());

        $I->dontSee($this->_freelancer->getName(), 'div#candidates');
        $I->dontSee($this->_freelancer->getShortDescription(), 'div#candidates');
        $I->dontSee('Jóváhagyás', 'div#candidates');

        $I->dontSee($this->_freelancer->getName(), 'div#participants');
        $I->dontSee($this->_freelancer->getShortDescription(), 'div#participants');
        $this->logout();
    }

    protected function apply(\AcceptanceTester $I, Entity_User $user, $success)
    {
        $data = [
            'project_id' => $this->_project->getId(),
            'user_id' => $user->getId(),
            'extra_data' => [
                'message' => $user->getShortDescription()
            ]
        ];

        $content = HttpHelper::sendPost('projectpartner/ajax/apply', $data);
        $this->_projectPartnerId = Arr::get($content, 'id', 0);

        $I->amOnPage('/szabaduszo-projekt/' . $this->_project->getSlug());

        if ($success) {
            $I->dontSee('Jelentkezés', 'div.panel-body a');
            $I->see('Visszavonás', 'div.panel-body a');
            $I->see($user->getName(), 'div#candidates');
            $I->see($user->getShortDescription(), 'div#candidates');
        } else {
            $I->dontSee('Jelentkezés', 'div.panel-body a');
            $I->dontSee('Visszavonás', 'div.panel-body a');
            $I->dontSee($user->getName(), 'div#candidates');
        }
    }

    protected function undoApplication()
    {
        $data = [
            'project_partner_id' => $this->_projectPartnerId,
            'extra_data' => [
                'message' => 'Visszavonás'
            ]
        ];

        HttpHelper::sendPost('projectpartner/ajax/undoApplication', $data);
    }

    protected function approveApplication()
    {
        $data = [
            'project_partner_id' => $this->_projectPartnerId,
            'user_id' => $this->_employer->getId(),
            'extra_data' => [
                'message' => 'Jóváhagyom'
            ]
        ];

        HttpHelper::sendPost('projectpartner/ajax/approveApplication', $data);
    }

    protected function rejectApplication()
    {
        $data = [
            'project_partner_id' => $this->_projectPartnerId,
            'user_id' => $this->_employer->getId(),
            'extra_data' => [
                'message' => 'Elutasítom'
            ]
        ];

        HttpHelper::sendPost('projectpartner/ajax/rejectApplication', $data);
    }

    protected function logout()
    {
        Auth::instance()->logout(true);
    }
}
