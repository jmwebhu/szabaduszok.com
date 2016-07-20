<?php

class UserTest extends Unittest_TestCase
{
	/**
	 * @dataProvider testNameDataProvider
	 */
	public function testName($result, $expected)
	{
		$this->assertEquals($result, $expected);
	}
        
        /**
	 * @dataProvider testFreelancerNameDataProvider
	 */
	public function testFreelancerName($result, $expected)
	{
            $this->assertEquals($result, $expected);
	}

	/**
	 * @dataProvider testCollectSearchTextDataProvider
	 */
	public function testCollectSearchText($result, $expected)
	{
		$this->assertEquals($result, $expected);
	}

	public function testHasUserNotificationByOrOneSkillOk()
	{
		$user 				= new Model_User();

		$notification = $this->getMockBuilder('\Model_User_Project_Notification')
			->getMock();

		$notification->expects($this->once())
			->method('getSkillIdsByUser')
			->will($this->returnValue([1, 2]));

		$projectSkillIds 	= [1];	
		
		$this->assertTrue($this->invokeMethod($user, 'hasUserNotificationByOr', [$projectSkillIds, $notification]));
	}

	public function testHasUserNotificationByOrMoreSkillOk()
	{
		$user 				= new Model_User();

		$notification = $this->getMockBuilder('\Model_User_Project_Notification')
			->getMock();

		$notification->expects($this->once())
			->method('getSkillIdsByUser')
			->will($this->returnValue([1, 2, 3, 5, 8]));

		$projectSkillIds 	= [1, 4, 5, 7];	
		
		$this->assertTrue($this->invokeMethod($user, 'hasUserNotificationByOr', [$projectSkillIds, $notification]));
	}

	public function testHasUserNotificationByOrOneSkillNotOk()
	{
		$user 				= new Model_User();

		$notification = $this->getMockBuilder('\Model_User_Project_Notification')
			->getMock();

		$notification->expects($this->once())
			->method('getSkillIdsByUser')
			->will($this->returnValue([1, 2, 3, 5, 8]));

		$projectSkillIds 	= [4];	
		
		$this->assertFalse($this->invokeMethod($user, 'hasUserNotificationByOr', [$projectSkillIds, $notification]));
	}

	public function testHasUserNotificationByOrMoreSkillNotOk()
	{
		$user 				= new Model_User();

		$notification = $this->getMockBuilder('\Model_User_Project_Notification')
			->getMock();

		$notification->expects($this->once())
			->method('getSkillIdsByUser')
			->will($this->returnValue([1, 2, 3, 5, 8]));

		$projectSkillIds 	= [4, 6, 7, 12];	
		
		$this->assertFalse($this->invokeMethod($user, 'hasUserNotificationByOr', [$projectSkillIds, $notification]));
	}

	public function testHasUserNotificationByOrEmptyUserSkillsOk()
	{
		$user 				= new Model_User();

		$notification = $this->getMockBuilder('\Model_User_Project_Notification')
			->getMock();

		$notification->expects($this->once())
			->method('getSkillIdsByUser')
			->will($this->returnValue([]));

		$projectSkillIds 	= [4, 5, 1];	
		
		$this->assertTrue($this->invokeMethod($user, 'hasUserNotificationByOr', [$projectSkillIds, $notification]));
	}	

	public function testHasUserNotificationByAndOneSkillNotOk()
	{
		$user 				= new Model_User();

		$notification = $this->getMockBuilder('\Model_User_Project_Notification')
			->getMock();

		$notification->expects($this->once())
			->method('getSkillIdsByUser')
			->will($this->returnValue([1, 2]));

		$projectSkillIds 	= [3];	
		
		$this->assertFalse($this->invokeMethod($user, 'hasUserNotificationByAnd', [$projectSkillIds, $notification]));
	}

	public function testHasUserNotificationByAndMoreSkillNotOk()
	{
		$user 				= new Model_User();

		$notification = $this->getMockBuilder('\Model_User_Project_Notification')
			->getMock();

		$notification->expects($this->once())
			->method('getSkillIdsByUser')
			->will($this->returnValue([1, 2, 3, 5, 8]));

		$projectSkillIds 	= [1, 4, 5, 7];	
		
		$this->assertFalse($this->invokeMethod($user, 'hasUserNotificationByAnd', [$projectSkillIds, $notification]));
	}

	public function testHasUserNotificationByAndOneSkillOk()
	{
		$user 				= new Model_User();

		$notification = $this->getMockBuilder('\Model_User_Project_Notification')
			->getMock();

		$notification->expects($this->once())
			->method('getSkillIdsByUser')
			->will($this->returnValue([1]));

		$projectSkillIds 	= [1];	
		
		$this->assertTrue($this->invokeMethod($user, 'hasUserNotificationByAnd', [$projectSkillIds, $notification]));
	}

	public function testHasUserNotificationByAndMoreSkillOk()
	{
		$user 				= new Model_User();

		$notification = $this->getMockBuilder('\Model_User_Project_Notification')
			->getMock();

		$notification->expects($this->once())
			->method('getSkillIdsByUser')
			->will($this->returnValue([1, 2, 3, 5, 8]));

		$projectSkillIds 	= [1, 2, 3];	
		
		$this->assertTrue($this->invokeMethod($user, 'hasUserNotificationByOr', [$projectSkillIds, $notification]));
	}

	public function testHasUserNotificationByAndEmptyUserSkillsOk()
	{
		$user 				= new Model_User();

		$notification = $this->getMockBuilder('\Model_User_Project_Notification')
			->getMock();

		$notification->expects($this->once())
			->method('getSkillIdsByUser')
			->will($this->returnValue([]));

		$projectSkillIds 	= [4, 5, 1];	
		
		$this->assertTrue($this->invokeMethod($user, 'hasUserNotificationByAnd', [$projectSkillIds, $notification]));
	}	

	public function testCollectSearchTextDataProvider()
	{
		$user = new Model_User();
		$user->firstname = 'Martin';
		$user->lastname  = 'Joó';
		$user->company_name  = null;
		$user->is_company = false;
		$user->short_description = 'Helló, ez egy rövid bemutatkozás, Joó Martin vagyok. 5 éve foglalkzom webfejlesztéssel.';
		$user->address_city = 'Szombathely';

		$expected = 'Joó Martin Helló, ez egy rövid bemutatkozás, Joó Martin vagyok. 5 éve foglalkzom webfejlesztéssel. ' . date('Y-m-d') . ' Szombathely   ';

		$userCompany = new Model_User();
		$userCompany->firstname = 'Martin';
		$userCompany->lastname  = 'Joó';
		$userCompany->company_name  = 'Jmweb Zrt.';
		$userCompany->is_company = true;
		$userCompany->short_description = 'Helló, ez egy rövid bemutatkozás, Joó Martin vagyok. 5 éve foglalkzom webfejlesztéssel.';
		$userCompany->address_city = 'Szombathely';

		$expectedCompany = 'Joó Martin Helló, ez egy rövid bemutatkozás, Joó Martin vagyok. 5 éve foglalkzom webfejlesztéssel. ' . date('Y-m-d') . ' Szombathely Jmweb Zrt.   ';

		return [
			[$user->collectSearchText(), $expected],
			[$userCompany->collectSearchText(), $expectedCompany]
		];
	}                

	public function testNameDataProvider()
	{
		$userFullName = new Model_User();
		$userFullName->firstname = 'Martin';
		$userFullName->lastname  = 'Joó';
		$userFullName->company_name  = null;
                $userFullName->type          = 2;

		$userFullNameCompanyName = new Model_User();
		$userFullNameCompanyName->firstname = 'Martin';
		$userFullNameCompanyName->lastname  = 'Joó';
		$userFullNameCompanyName->company_name  = 'Jmweb Zrt.';
                $userFullNameCompanyName->type          = 2;

		$userFirstNameNoLastnameNoCompanyName = new Model_User();
		$userFirstNameNoLastnameNoCompanyName->firstname = 'Martin';
		$userFirstNameNoLastnameNoCompanyName->lastname  = null;
		$userFirstNameNoLastnameNoCompanyName->company_name  = null;
                $userFirstNameNoLastnameNoCompanyName->type          = 2;

		$userFirstNameNoLastnameCompanyName = new Model_User();
		$userFirstNameNoLastnameCompanyName->firstname = 'Martin';
		$userFirstNameNoLastnameCompanyName->lastname  = null;
		$userFirstNameNoLastnameCompanyName->company_name  = 'Jmweb Zrt.';
                $userFirstNameNoLastnameCompanyName->type          = 2;

		$userNoNameCompanyName = new Model_User();
		$userNoNameCompanyName->firstname = null;
		$userNoNameCompanyName->lastname  = null;
		$userNoNameCompanyName->company_name  = 'Jmweb Zrt.';
                $userNoNameCompanyName->type          = 2;

		$userNoNameNoCompanyName = new Model_User();
		$userNoNameNoCompanyName->firstname = null;
		$userNoNameNoCompanyName->lastname  = null;
		$userNoNameNoCompanyName->company_name  = null;
                $userNoNameNoCompanyName->type          = 2;
                
                $userFullNameFreelancer = new Model_User();
		$userFullNameFreelancer->firstname = 'Martin';
		$userFullNameFreelancer->lastname  = 'Joó';
		$userFullNameFreelancer->company_name  = null;
                $userFullNameFreelancer->type          = 1;		                            

		return [
			[$userFullName->name(), 'Joó Martin'],
			[$userFullNameCompanyName->name(), 'Joó Martin'],
			[$userFirstNameNoLastnameNoCompanyName->name(), null],
			[$userFirstNameNoLastnameCompanyName->name(), 'Jmweb Zrt.'],
			[$userNoNameCompanyName->name(), 'Jmweb Zrt.'],
			[$userNoNameNoCompanyName->name(), ''],
		];
	}
        
        public function testFreelancerNameDataProvider()
        {
            $userFullName = new Model_User();
            $userFullName->firstname = 'Martin';
            $userFullName->lastname  = 'Joó';
            $userFullName->type          = 1;

            $userFirstNameNoLastname = new Model_User();
            $userFirstNameNoLastname->firstname = 'Martin';
            $userFirstNameNoLastname->lastname  = null;
            $userFirstNameNoLastname->type          = 1;

            $userLastNameNoFirstname = new Model_User();
            $userLastNameNoFirstname->firstname = null;
            $userLastNameNoFirstname->lastname  = 'Joó';
            $userLastNameNoFirstname->type          = 1;

            $userNoName = new Model_User();
            $userNoName->firstname = null;
            $userNoName->lastname  = null;
            $userNoName->type          = 1;
            
            return [
                [$userFullName->freelancerName(), 'Martin'],
                [$userFirstNameNoLastname->freelancerName(), 'Martin'],
                [$userLastNameNoFirstname->freelancerName(), 'Joó'],
                [$userNoName->freelancerName(), 'Szabadúszó'],
            ];
        }
        
    /**
     * @covers Model_User::addToMailService
     */
    public function testAddToMailService()
    {
        $api = Api_Mailservice::instance();
        
        $user1          = new Model_User();
        $user1->user_id = null;
        $user1->type    = 1;
        
        $result1 = $user1->addToMailService($api, $user1->type, $user1->user_id);                
        
        $user2          = new Model_User();
        $user2->user_id = 1;
        $user2->type    = 1;
        
        $result2 = $user2->addToMailService($api, $user2->type, $user2->user_id);
        
        $user3          = new Model_User();
        $user3->user_id = null;
        $user3->type    = 2;
        
        $result3 = $user3->addToMailService($api, $user3->type, $user3->user_id);
        
        $user4          = new Model_User();
        $user4->user_id = 1;
        $user4->type    = 2;
        
        $result4 = $user4->addToMailService($api, $user4->type, $user4->user_id);
 
        $this->assertEquals('subscribeFreelancer', $result1);        
        $this->assertEquals('updateFreelancer', $result2);
        $this->assertEquals('subscribeProjectowner', $result3);
        $this->assertEquals('updateProjectowner', $result4);
    }
	
	/**
	 * @covers Model_User::addProfiles
	 */
	public function testAddProfiles()
	{
		$post = [
			'profiles' => [
				'https://twitter.com/dzsooo', 'https://hu.linkedin.com/in/joó-martin-a4890b11a'
			]			
		];
		
		$profile	= new Model_Profile();
		$user		= new Model_User();
		
		$user->email = time() . '@szabaduszok.com';
		$user->save();
		
		$this->invokeMethod($user, 'addProfiles', [$post, $profile]);
		
		$userProfileIds = [];
		$temp = DB::select()->from('users_profiles')->where('user_id', '=', $user->pk())->execute()->as_array();
		
		foreach ($temp as $item)
		{
			$userProfileIds[] = $item['profile_id'];
		}
		
		$this->assertTrue(in_array(4, $userProfileIds));
		$this->assertTrue(in_array(1, $userProfileIds));
	}
	
	/**
	 * @covers Model_User::postWebpage
	 * @dataProvider testFixWebpageDataProvider
	 * 
	 * @group webpage
	 */
	public function testFixWebpage($expected, $actual)
	{
		$this->assertEquals($expected, $actual);
	}
	
	public function testFixWebpageDataProvider()
	{
		$user = new Model_User();
		
		return [
			['http://jmweb.hu', $this->invokeMethod($user, 'fixWebpage', [['webpage' => 'jmweb.hu']])],
			['http://jmweb.hu', $this->invokeMethod($user, 'fixWebpage', [['webpage' => 'http://jmweb.hu']])],
			['https://jmweb.hu', $this->invokeMethod($user, 'fixWebpage', [['webpage' => 'https://jmweb.hu']])],
			['', $this->invokeMethod($user, 'fixWebpage', [['webpage' => '']])],
			[null, $this->invokeMethod($user, 'fixWebpage', [['webpage' => null]])]
		];
	}
}