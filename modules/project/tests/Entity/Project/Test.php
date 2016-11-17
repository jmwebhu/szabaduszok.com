<?php

class Entity_Project_Test extends Unittest_TestCase 
{
	/**
	 * @var Entity_User_Employer
	 */
	private static $_employer = null;

	/**
	 * @covers Entity_Project::submit()
	 */
	public function testSubmitOk()
	{
		$project 	= new Entity_Project();
		$data		= [
			'user_id'					=> self::$_employer->getUserId(),
			'name'                      => 'Teszt projekt',
			'short_description'         => 'Teszt projekt Teszt projekt',
            'long_description'          => 'Teszt projekt Teszt projekt Teszt projekt',
            'email'                     => uniqid() . '@szabaduszok.com',
            'phonenumber'               => '06301923380',
            'salary_type'               => 1,
            'salary_low'                => 3200
		];                  

		$project->submit($data);

		$this->assertNotEmpty($project->getProjectId());
		$this->assertNotEmpty($project->getEmail());
		$this->assertNotEmpty($project->getSearchText());
		$this->assertNotEmpty($project->getExpirationDate());
		$this->assertNotEmpty($project->getSlug());
		$this->assertNotEmpty($project->getCreatedAt());

		$this->assertEquals(self::$_employer->getUserId(), $project->getUserId());	
		$this->assertEquals('Teszt projekt', $project->getName());	
		$this->assertEquals('Teszt projekt Teszt projekt', $project->getShortDescription());	
		$this->assertEquals('Teszt projekt Teszt projekt Teszt projekt', $project->getLongDescription());	
		$this->assertEquals('06301923380', $project->getPhonenumber());	
		$this->assertEquals(1, $project->getSalaryType());	
		$this->assertEquals(3200, $project->getSalaryLow());	
		$this->assertEquals(null, $project->getSalaryHigh());	
		$this->assertEquals(1, $project->getIsActive());	

		return $project;
	}

	/**
	 * @covers Entity_Project::submit()
	 * @expectedException ORM_Validation_Exception
	 */
	public function testSubmitNotOk()
	{
		$project 	= new Entity_Project();
		$data		= [
			'user_id'					=> self::$_employer->getUserId(),
			'short_description'         => 'Teszt projekt Teszt projekt',
            'long_description'          => 'Teszt projekt Teszt projekt Teszt projekt',
            'email'                     => uniqid() . '@szabaduszok.com',
            'phonenumber'               => '06301923380',
            'salary_type'               => 1,
            'salary_low'                => 3200
		];                  

		$project->submit($data);
	}

	/**
	 * @covers Entity_Project::inactivate()
	 * @depends testSubmitOk
	 */
	public function testInactivateOk(Entity_Project $project)
	{
		$project->inactivate();
		
		$this->assertEquals(0, $project->getIsActive());	
	}

	public static function setUpBeforeClass()
	{
		$employerData = [
            'firstname'             => 'Martin',
            'lastname'              => 'Joó',
            'email'                 => uniqid() . '@szabaduszok.com',
            'password'              => 'asdfasdf123',
            'password_confirm'      => 'asdfasdf123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380'
        ];

        $employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $employer->submitUser($employerData);

        self::$_employer = $employer;
	}

	public static function tearDownAfterClass()
	{
		if (self::$_employer) {
			self::$_employer->getModel()->delete();
		}
	}
}