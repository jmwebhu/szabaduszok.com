<?php

/**
 * EZ A TESZT ELES ADATBAZIST HASZNAL MOCK HELYETT
 * setUp -kor truncate -el minden hasznalt tablat
 */

class ProjectTest extends PHPUnit_Framework_TestCase
{
    protected static $_users        = [];
    protected static $_industries   = [];
    protected static $_professions  = [];
    protected static $_skills       = [];
    protected static $_project      = null;
    
    public static function setUpBeforeClass()
    {                
        $industryModel      = new Model_Industry();
        $professionModel    = new Model_Profession();
        $skillModel         = new Model_Skill();
        $userModel          = new Model_User();
        $projectModel       = new Model_Project();
        
        $industryModel->truncate();
        $professionModel->truncate();
        $skillModel->truncate();
        $userModel->truncate();
        $projectModel->truncate();   
        
        $industry = new Model_Industry();
        $industry->name    = 'industry1';
        $industry->slug    = 'industry-1';

        $industry->save();

        $industry2 = new Model_Industry();
        $industry2->name    = 'industry2';
        $industry2->slug    = 'industry-2';

        $industry2->save();

        $profession = new Model_Profession();
        $profession->name    = 'profession1';
        $profession->slug    = 'profession-1';

        $profession->save();

        $profession2 = new Model_Profession();
        $profession2->name    = 'profession2';
        $profession2->slug    = 'profession-2';

        $profession2->save();

        $skill = new Model_Skill();
        $skill->name    = 'skill1';
        $skill->slug    = 'skill-1';

        $skill->save();

        $skill2 = new Model_Skill();
        $skill2->name    = 'skill2';
        $skill2->slug    = 'skill-2';

        $skill2->save();

        $skill3 = new Model_Skill();
        $skill3->name    = 'skill3';
        $skill3->slug    = 'skill-3';

        $skill3->save();

        $skill4 = new Model_Skill();
        $skill4->name    = 'skill4';
        $skill4->slug    = 'skill-4';

        $skill4->save();                       
        
        $user = new Model_User();
        $user->lastname                    = 'User';
        $user->firstname                   = '1';
        $user->email                       = 'user1@szabaduszok.com';
        $user->password                    = 'asdf123';
        $user->slug                        = 'user-1';
        $user->type                        = 1;
        $user->skill_relation              = 1;    // VAGY
        $user->need_project_notification   = 1;

        $user->save();       

        $user->add('industries', $industry);
        $user->add('professions', $profession);
        $user->add('skills', $skill);
        $user->add('skills', $skill2);   
        
        $userProjectNotification = new Model_User_Project_Notification();
        $userProjectNotification->user = $user;
        $userProjectNotification->skill = $skill;
        $userProjectNotification->save();
        
        $userProjectNotification2 = new Model_User_Project_Notification();
        $userProjectNotification2->user = $user;
        $userProjectNotification2->skill = $skill2;
        $userProjectNotification2->save();

        $user2 = new Model_User();  
        $user2->lastname                    = 'User';
        $user2->firstname                   = '2';
        $user2->email                       = 'user2@szabaduszok.com';
        $user2->password                    = 'asdf123';
        $user2->slug                        = 'user-2';
        $user2->type                        = 1;
        $user2->skill_relation              = 1;    // VAGY
        $user2->need_project_notification   = 0;

        $user2->save();

        $user2->add('industries', $industry);
        $user2->add('professions', $profession2);
        $user2->add('skills', $skill2);
        $user2->add('skills', $skill3);  
        
        $userProjectNotification3 = new Model_User_Project_Notification();
        $userProjectNotification3->user = $user2;
        $userProjectNotification3->skill = $skill2;
        $userProjectNotification3->save();
        
        $userProjectNotification4 = new Model_User_Project_Notification();
        $userProjectNotification4->user = $user2;
        $userProjectNotification4->skill = $skill3;
        $userProjectNotification4->save();

        $user3 = new Model_User();
        $user3->lastname                    = 'User';
        $user3->firstname                   = '3';
        $user3->email                       = 'user3@szabaduszok.com';
        $user3->password                    = 'asdf123';
        $user3->slug                        = 'user-3';
        $user3->type                        = 1;
        $user3->skill_relation              = 2;    // ES
        $user3->need_project_notification   = 1;

        $user3->save();

        $user3->add('industries', $industry);
        $user3->add('professions', $profession);
        $user3->add('professions', $profession2);
        $user3->add('skills', $skill2);
        $user3->add('skills', $skill3);  
        $user3->add('skills', $skill4); 
        
        $userProjectNotification5 = new Model_User_Project_Notification();
        $userProjectNotification5->user = $user3;
        $userProjectNotification5->skill = $skill2;
        $userProjectNotification5->save();
        
        $userProjectNotification6 = new Model_User_Project_Notification();
        $userProjectNotification6->user = $user3;
        $userProjectNotification6->skill = $skill3;
        $userProjectNotification6->save();
        
        $userProjectNotification7 = new Model_User_Project_Notification();
        $userProjectNotification7->user = $user3;
        $userProjectNotification7->skill = $skill4;
        $userProjectNotification7->save();

        $user4 = new Model_User();
        $user4->lastname                    = 'User';
        $user4->firstname                   = '4';
        $user4->email                       = 'user4@szabaduszok.com';
        $user4->password                    = 'asdf123';
        $user4->slug                        = 'user-4';
        $user4->type                        = 2;

        $user4->save();

        $user4->add('industries', $industry);
        $user4->add('professions', $profession);
        $user4->add('professions', $profession2);

        self::$_users = [
            $user, $user2, $user3, $user4
        ];

        self::$_industries = [
            $industry, $industry2
        ];

        self::$_professions = [
            $profession, $profession2
        ];

        self::$_skills = [
            $skill, $skill2, $skill3, $skill4
        ];
        
        parent::setUpBeforeClass();
    }
    
    public static function tearDownAfterClass() 
    {
    }
    
    /**
     * @group submit
     * @covers Model_Project::submit
     * @group bugs
     * @group bug.v21.4
     */
    public function testSubmit()
    {
        $user = self::$_users[0];
        $data = [
            'user_id'           => $user->pk(),
            'name'              => 'Projekt 1',
            'short_description' => 'Projekt 1 rövid leírás',
            'long_description'  => 'Projekt 1 hosszú leírás',
            'email'             => $user->email,
            'phonenumber'       => $user->phonenumber,
            'salary_type'       => 1,
            'salary_low'        => 2500,
            'industries'        => [self::$_industries[0]->industry_id],
            'professions'       => [
                self::$_professions[0]->profession_id,
                self::$_professions[1]->profession_id,
            ],
            'skills'            => [
                self::$_skills[1]->skill_id,
                self::$_skills[3]->skill_id,
            ]
            
        ];
        
        $projectModel   = new Model_Project();
        $project        = $projectModel->submit($data);     
        
        self::$_project = $project;
        
        $notifications  = $project->notifications->find_all();
        $slugs          = [];                            
        
        /**
         * @todo notifications, queue
         */
        
        foreach ($notifications as $notification)
        {
            /**
             * @var $notification Model_Project_Notification
             */
            $slugs[] = $notification->user->slug;
        }
        
        $projects = Cache::instance()->get('projects');
        
        $this->assertEquals('projekt-1', $project->slug);
        $this->assertEquals(0, $project->salary_high);
        
        $this->assertTrue(in_array('user-1', $slugs));        
        $this->assertFalse(in_array('user-2', $slugs));
        $this->assertFalse(in_array('user-3', $slugs));                
        
        $this->assertEquals([self::$_industries[0]->pk()], $project->getRelationIds('industries'));
        $this->assertEquals([self::$_professions[0]->pk(), self::$_professions[1]->pk()], $project->getRelationIds('professions'));
        $this->assertEquals([self::$_skills[1]->pk(), self::$_skills[3]->pk()], $project->getRelationIds('skills')); 
        
        $this->assertNotEmpty($projects[$project->pk()]);
        $this->assertEquals('projekt-1', $projects[$project->pk()]->slug);
    }
    
    /**
     * @group submit
     * @covers Model_Project::submit
     */
    public function testEdit()
    {
        $user = self::$_users[0];
        $data = [
            'project_id'        => self::$_project->pk(),   
            'user_id'           => $user->pk(),
            'name'              => 'Projekt 1 módosítva',
            'short_description' => 'Projekt 1 rövid leírás módosítva',
            'long_description'  => 'Projekt 1 hosszú leírás módosítva',
            'email'             => $user->email,
            'phonenumber'       => $user->phonenumber,
            'salary_type'       => 2,
            'salary_low'        => 50000,
            'salary_high'       => 75000,
            'industries'        => [self::$_industries[0]->industry_id],
            'professions'       => [
                self::$_professions[0]->profession_id,
                self::$_professions[1]->profession_id,
            ],
            'skills'            => [
                self::$_skills[1]->skill_id,
                self::$_skills[2]->skill_id,
                self::$_skills[3]->skill_id,
            ]            
        ];
        
        $projectModel   = new Model_Project();
        $project        = $projectModel->submit($data);     
        
        self::$_project = $project;
        
        $notifications  = $project->notifications->find_all();
        $slugs          = [];                            
        
        /**
         * @todo notifications, queue
         */
        
        foreach ($notifications as $notification)
        {
            /**
             * @var $notification Model_Project_Notification
             */
            $slugs[] = $notification->user->slug;
        }
        
        $projects = Cache::instance()->get('projects');
        
        $this->assertEquals('projekt-1-modositva', $project->slug);
        
        $this->assertTrue(in_array('user-1', $slugs));        
        $this->assertFalse(in_array('user-2', $slugs));
        $this->assertFalse(in_array('user-3', $slugs));                
        
        $this->assertEquals([self::$_industries[0]->pk()], $project->getRelationIds('industries'));
        $this->assertEquals([self::$_professions[0]->pk(), self::$_professions[1]->pk()], $project->getRelationIds('professions'));
        $this->assertEquals([self::$_skills[1]->pk(), self::$_skills[2]->pk(), self::$_skills[3]->pk()], $project->getRelationIds('skills'));        
        
        $this->assertNotEmpty($projects[$project->pk()]);
        $this->assertEquals('projekt-1-modositva', $projects[$project->pk()]->slug);
    }
}