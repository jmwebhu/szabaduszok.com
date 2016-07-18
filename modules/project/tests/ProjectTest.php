<?php

class ProjectTest extends Unittest_TestCase
{        
        public function tearDown() {
            DB::delete('industries')->where('name', 'IN', ['industry100', 'industry200'])->execute();
            DB::delete('professions')->where('name', 'IN', ['profession100', 'profession200', 'profession300'])->execute();
            DB::delete('skills')->where('name', 'IN', ['skill100', 'skill200', 'skill300'])->execute();
        
            parent::tearDown();
        }
    
    public function testAddRelations()
    {
        $industry = new Model_Industry();
        $industry->name    = 'industry100';

        $industry->save();
        $industry->saveSlug();

        $industry2 = new Model_Industry();
        $industry2->name    = 'industry200';

        $industry2->save();
        $industry2->saveSlug();

        $profession = new Model_Profession();
        $profession->name    = 'profession100';

        $profession->save();
        $profession->saveSlug();

        $profession2 = new Model_Profession();
        $profession2->name    = 'profession200';

        $profession2->save();
        $profession2->saveSlug();

        $skill = new Model_Skill();
        $skill->name    = 'skill100';

        $skill->save();
        $skill->saveSlug();

        $skill2 = new Model_Skill();
        $skill2->name    = 'skill200';

        $skill2->save();
        $skill2->saveSlug();
        
        $data = [
            'industries'    => [$industry->pk(), $industry2->pk()],
            'professions'   => [$profession->pk(), 'profession300', $profession2->pk()],
            'skills'        => [$skill->pk(), $skill2->pk(), 'skill300']
        ]; 
        
        $user = new Model_User();
        $user->lastname                    = 'User';
        $user->firstname                   = '100';
        $user->email                       = 'user' . time() . '@szabaduszok.com';
        $user->password                    = 'asdf123';
        $user->type                        = 2;               

        $user->save();                      
        
        $project = new Model_Project();
        $project->project_id = 999;
        
        $this->invokeMethod($project, 'addRelations', [$data]);        
        
        $industryIds = [];
        $professionIds = [];
        $skillIds = [];
        
        foreach ($project->industries->find_all() as $industryTemp)
        {
            $industryIds[] = $industryTemp->pk();
        }
        
        foreach ($project->professions->find_all() as $professionTemp)
        {
            $professionIds[] = $professionTemp->pk();
        }
        
        foreach ($project->skills->find_all() as $skillTemp)
        {
            $skillIds[] = $skillTemp->pk();
        }
        
        $idData = [
            'industries'    => [],
            'professions'   => [],
            'skills'        => [],
        ];
        
        foreach ($data['industries'] as $value)
        {
            $id = $value;
            if (!Text::isId($id))
            {
                $temp = new Model_Industry();
                $temp = $temp->where('name', '=', $value)->limit(1)->find();
                
                $id = $temp->pk();
            }
            
            $idData['industries'][] = $id;
        }
        
        foreach ($data['professions'] as $value)
        {
            $id = $value;
            if (!Text::isId($id))
            {
                $temp = new Model_Profession();
                $temp = $temp->where('name', '=', $value)->limit(1)->find();
                
                $id = $temp->pk();
            }
            
            $idData['professions'][] = $id;
        }
        
        foreach ($data['skills'] as $value)
        {
            $id = $value;
            if (!Text::isId($id))
            {
                $temp = new Model_Skill();
                $temp = $temp->where('name', '=', $value)->limit(1)->find();
                
                $id = $temp->pk();
            }
            
            $idData['skills'][] = $id;
        }
        
        foreach ($idData['industries'] as $id)
        {
            $this->assertTrue(in_array($id, $industryIds));
        }
        
        foreach ($idData['professions'] as $id)
        {
            $this->assertTrue(in_array($id, $professionIds));
        }
        
        foreach ($idData['skills'] as $id)
        {
            $this->assertTrue(in_array($id, $skillIds));
        }                           
    }
    
    /**
     * @group search
     * @covers Model_Project::searchBySkillsOr
     */
    public function testSearchBySkillsOrOk()
    {        
        $project1 = new Model_Project();        
        $project1->project_id = 1;
        $project1->name = 'first';
        
        $projectSkill1  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills1 = [
            1 => [
                1, 2, 3, 8, 10
            ]
        ];
        
        $projectSkill1->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills1));
        
        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';
        
        $projectSkill2  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills2 = [
            2 => [
                1, 8, 10
            ]
        ];
        
        $projectSkill2->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills2));
        
        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->name = 'third';
        
        $projectSkill3  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills3 = [
            3 => [
                1
            ]
        ];
        
        $projectSkill3->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills3));
        
        $project4 = new Model_Project();
        $project4->project_id = 4;
        $project4->name = 'third';
        
        $projectSkill4  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills4 = [
            4 => [
                3
            ]
        ];
        
        $projectSkill4->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills4));
        
        $project5 = new Model_Project();
        $project5->project_id = 5;
        $project5->name = 'third';
        
        $projectSkill5  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills5 = [
            5 => []
        ];
        
        $projectSkill5->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills5));
        
        $skills   = [1, 3];

        $this->assertTrue($this->invokeMethod($project1, 'searchBySkillsOr', [$project1, $skills, $projectSkill1]));        
        $this->assertTrue($this->invokeMethod($project2, 'searchBySkillsOr', [$project2, $skills, $projectSkill2]));        
        $this->assertTrue($this->invokeMethod($project3, 'searchBySkillsOr', [$project3, $skills, $projectSkill3]));        
        $this->assertTrue($this->invokeMethod($project4, 'searchBySkillsOr', [$project4, $skills, $projectSkill4]));        
        $this->assertTrue($this->invokeMethod($project5, 'searchBySkillsOr', [$project5, $skills, $projectSkill5]));        
    }  
    
    /**
     * @group search
     * @covers Model_Project::searchBySkillsOr
     */
    public function testSearchBySkillsOrNotOk()
    {        
        $project1 = new Model_Project();        
        $project1->project_id = 1;
        $project1->name = 'first';
        
        $projectSkill1  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills1 = [
            1 => [
                2, 8, 10
            ]
        ];
        
        $projectSkill1->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills1));
        
        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';
        
        $projectSkill2  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills2 = [
            2 => [
                8
            ]
        ];
        
        $projectSkill2->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills2));               
        
        $skills   = [1, 3];

        $this->assertFalse($this->invokeMethod($project1, 'searchBySkillsOr', [$project1, $skills, $projectSkill1]));        
        $this->assertFalse($this->invokeMethod($project2, 'searchBySkillsOr', [$project2, $skills, $projectSkill2]));        
    }  
    
    /**
     * @group search
     * @covers Model_Project::searchBySkillsAnd
     */
    public function testSearchBySkillsAndOk()
    {        
        $project1 = new Model_Project();        
        $project1->project_id = 1;
        $project1->name = 'first';
        
        $projectSkill1  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills1 = [
            1 => [
                1, 2, 3, 8, 10
            ]
        ];
        
        $projectSkill1->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills1));               
        
        $project5 = new Model_Project();
        $project5->project_id = 5;
        $project5->name = 'third';
        
        $projectSkill5  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills5 = [
            5 => []
        ];
        
        $projectSkill5->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills5));
        
        $project6 = new Model_Project();
        $project6->project_id = 6;
        $project6->name = 'sixth';
        
        $projectSkill6  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills6 = [
            6 => [
                1, 3
            ]
        ];
        
        $projectSkill6->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills6));
        
        $skills   = [1, 3];

        $this->assertTrue($this->invokeMethod($project1, 'searchBySkillsAnd', [$project1, $skills, $projectSkill1]));                   
        $this->assertTrue($this->invokeMethod($project5, 'searchBySkillsAnd', [$project5, $skills, $projectSkill5]));        
        $this->assertTrue($this->invokeMethod($project6, 'searchBySkillsAnd', [$project6, $skills, $projectSkill6]));        
    } 
    
    /**
     * @group search
     * @covers Model_Project::searchBySkillsAnd
     */
    public function testSearchBySkillsAndNotOk()
    {        
        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';
        
        $projectSkill2  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills2 = [
            2 => [
                1, 8, 10
            ]
        ];
        
        $projectSkill2->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills2));
        
        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->name = 'third';
        
        $projectSkill3  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills3 = [
            3 => [
                1
            ]
        ];
        
        $projectSkill3->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills3));
        
        $project4 = new Model_Project();
        $project4->project_id = 4;
        $project4->name = 'third';
        
        $projectSkill4  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills4 = [
            4 => [
                3
            ]
        ];
        
        $projectSkill4->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills4));        
        
        $skills   = [1, 3];

        $this->assertFalse($this->invokeMethod($project2, 'searchBySkillsAnd', [$project2, $skills, $projectSkill2]));        
        $this->assertFalse($this->invokeMethod($project3, 'searchBySkillsAnd', [$project3, $skills, $projectSkill3]));        
        $this->assertFalse($this->invokeMethod($project4, 'searchBySkillsAnd', [$project4, $skills, $projectSkill4]));           
    }     
    
    /**
     * @dataProvider testGetSearchTextDataProvider
     */
    public function testgetSearchText($result, $expected)
    {
        $this->assertEquals($result, $expected);
    }

    public function testGetSearchTextDataProvider()
    {
        $this->markTestSkipped();
        $project = new Model_Project();
        $project->name = 'Folyamatos webfejlesztések';
        $project->short_description  = 'Lelkes, megbízható, kiegyensúlyozott webprogramozót keresek projektek, folyamatos web fejlesztések elvégzésére. Először kisebb otthonról végezhető munkák lennének, és ha együtt tudunk működni, akkor több weboldalam fejlesztésében részt vehetsz. Diáknak, kezdő programozónak is nyitott az állás.';
        $project->long_description  = 'Elvárások:

HTML
CSS
referencia munkák
angol tudás
határidők betartása
Előnyt jelent:

kereső marketing tudás (SEO)
fizetési módok webshopba integrálás ismerete
kreativitás, és önképzés (nem baj ha kezdő vagy, de a felmerülő feladatokat képes legyél önállóan megoldani)
Projektek amikben részt vehetsz:

parajdisokincsek.hu webfejlesztés
semmiszor.hu webfejlesztés
http://semmiszor.hu/stilettodress/ webfejlesztés
Jelentkezés: Fényképes önéletrajzodat, és motivációs leveledet várom, a szkladanyi.attila@parajdisokincsek.hu email címre. A motivációs levélben kitérhetsz a fenti 3 oldallal kapcsolatban milyen fejlesztési, egyéb ötleteid lennének.

';
        $project->email = 'joomartin@jmweb.hu';
        $project->phonenumber = '06301923380';

        $expected = 'Folyamatos webfejlesztések Lelkes, megbízható, kiegyensúlyozott webprogramozót keresek projektek, folyamatos web fejlesztések elvégzésére. Először kisebb otthonról végezhető munkák lennének, és ha együtt tudunk működni, akkor több weboldalam fejlesztésében részt vehetsz. Diáknak, kezdő programozónak is nyitott az állás. Elvárások:

HTML
CSS
referencia munkák
angol tudás
határidők betartása
Előnyt jelent:

kereső marketing tudás (SEO)
fizetési módok webshopba integrálás ismerete
kreativitás, és önképzés (nem baj ha kezdő vagy, de a felmerülő feladatokat képes legyél önállóan megoldani)
Projektek amikben részt vehetsz:

parajdisokincsek.hu webfejlesztés
semmiszor.hu webfejlesztés
http://semmiszor.hu/stilettodress/ webfejlesztés
Jelentkezés: Fényképes önéletrajzodat, és motivációs leveledet várom, a szkladanyi.attila@parajdisokincsek.hu email címre. A motivációs levélben kitérhetsz a fenti 3 oldallal kapcsolatban milyen fejlesztési, egyéb ötleteid lennének.

 joomartin@jmweb.hu 06301923380 ' . date('Y-m-d') . '    ';

        return [
            [$project->getSearchText(), $expected],
        ];
    }
    
    public function testSearchByRelationIndustriesOne()
    {        
        $project1 = new Model_Project();
        $project1->project_id = 1010;
        
        $project2 = new Model_Project();
        $project2->project_id = 1011;
        
        $industry1 = new Model_Industry();
        $industry1->industry_id = 342;
        
        $industry2 = new Model_Industry();
        $industry2->industry_id = 343;
        
        $projects = [$project1, $project2];
        
        $projectIndustryMock  = $this->getMockBuilder('\Model_Project_Industry')->getMock();
        $projectIndustries = [
            1010 => [
                $industry1->industry_id, $industry2->industry_id
            ],
            1011 => [
                $industry1->industry_id, $industry2->industry_id
            ]
        ];
        
        $projectIndustryMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectIndustries));
        
        $postIndustries1 = [342];
        
        $project = new Model_Project();        
        $result1 = $this->invokeMethod($project, 'searchByRelation', [$projects, $postIndustries1, $projectIndustryMock]);        
        $resultIds1 = [];                
        
        foreach ($result1 as $item)
        {            
            $resultIds1[] = $item->project_id;
        }
        
        $postIndustries2 = [343];
              
        $result2 = $this->invokeMethod($project, 'searchByRelation', [$projects, $postIndustries2, $projectIndustryMock]);        
        $resultIds2 = [];                
        
        foreach ($result2 as $item)
        {            
            $resultIds2[] = $item->project_id;
        }
        
        $postIndustries3 = [366];
              
        $result3 = $this->invokeMethod($project, 'searchByRelation', [$projects, $postIndustries3, $projectIndustryMock]);        
        $resultIds3 = [];                
        
        foreach ($result3 as $item)
        {            
            $resultIds3[] = $item->project_id;
        }
        
        $postIndustries4 = [];
              
        $result4 = $this->invokeMethod($project, 'searchByRelation', [$projects, $postIndustries4, $projectIndustryMock]);        
        $resultIds4 = [];                
        
        foreach ($result4 as $item)
        {            
            $resultIds4[] = $item->project_id;
        }
        
        $this->assertTrue(in_array($project1->project_id, $resultIds1));
        $this->assertTrue(in_array($project2->project_id, $resultIds1));
        
        $this->assertTrue(in_array($project1->project_id, $resultIds2));
        $this->assertTrue(in_array($project2->project_id, $resultIds2));
        
        $this->assertFalse(in_array($project1->project_id, $resultIds3));
        $this->assertFalse(in_array($project2->project_id, $resultIds3));
        
        $this->assertTrue(in_array($project1->project_id, $resultIds4));
        $this->assertTrue(in_array($project2->project_id, $resultIds4));
    }
    
    public function testSearchByRelationIndustriesMore()
    {        
        $project1 = new Model_Project();
        $project1->project_id = 1010;
        
        $project2 = new Model_Project();
        $project2->project_id = 1011;
        
        $industry1 = new Model_Industry();
        $industry1->industry_id = 342;
        
        $industry2 = new Model_Industry();
        $industry2->industry_id = 343;
        
        $projects = [$project1, $project2];
        
        $projectIndustryMock  = $this->getMockBuilder('\Model_Project_Industry')->getMock();
        $projectIndustries = [
            1010 => [
                $industry1->industry_id
            ],
            1011 => [
                $industry2->industry_id
            ]
        ];
        
        $projectIndustryMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectIndustries));
        
        $postIndustries1 = [342, 343];
        
        $project = new Model_Project();        
        $result1 = $this->invokeMethod($project, 'searchByRelation', [$projects, $postIndustries1, $projectIndustryMock]);        
        $resultIds1 = [];                
        
        foreach ($result1 as $item)
        {            
            $resultIds1[] = $item->project_id;
        }
        
        $postIndustries2 = [342];
        
        $result2 = $this->invokeMethod($project, 'searchByRelation', [$projects, $postIndustries2, $projectIndustryMock]);        
        $resultIds2 = [];                
        
        foreach ($result2 as $item)
        {            
            $resultIds2[] = $item->project_id;
        }
        
        $postIndustries3 = [343];
        
        $result3 = $this->invokeMethod($project, 'searchByRelation', [$projects, $postIndustries3, $projectIndustryMock]);        
        $resultIds3 = [];                
        
        foreach ($result3 as $item)
        {            
            $resultIds3[] = $item->project_id;
        }
        
        $postIndustries4 = [997, 983];
        
        $result4 = $this->invokeMethod($project, 'searchByRelation', [$projects, $postIndustries4, $projectIndustryMock]);        
        $resultIds4 = [];                
        
        foreach ($result4 as $item)
        {            
            $resultIds4[] = $item->project_id;
        }
        
        $postIndustries5 = [997, 983, 342];
        
        $result5 = $this->invokeMethod($project, 'searchByRelation', [$projects, $postIndustries5, $projectIndustryMock]);        
        $resultIds5 = [];                
        
        foreach ($result5 as $item)
        {            
            $resultIds5[] = $item->project_id;
        }
        
        $postIndustries6 = [997, 983, 343];
        
        $result6 = $this->invokeMethod($project, 'searchByRelation', [$projects, $postIndustries6, $projectIndustryMock]);        
        $resultIds6 = [];                
        
        foreach ($result6 as $item)
        {            
            $resultIds6[] = $item->project_id;
        }
        
        $postIndustries7 = [997, 983, 343, 674, 342];
        
        $result7 = $this->invokeMethod($project, 'searchByRelation', [$projects, $postIndustries7, $projectIndustryMock]);        
        $resultIds7 = [];                
        
        foreach ($result7 as $item)
        {            
            $resultIds7[] = $item->project_id;
        }
        
        $postIndustries8 = [997, 983, 345, 674, 349];
        
        $result8 = $this->invokeMethod($project, 'searchByRelation', [$projects, $postIndustries8, $projectIndustryMock]);        
        $resultIds8 = [];                
        
        foreach ($result8 as $item)
        {            
            $resultIds8[] = $item->project_id;
        }
        
        $this->assertTrue(in_array($project1->project_id, $resultIds1));
        $this->assertTrue(in_array($project2->project_id, $resultIds1));
        
        $this->assertTrue(in_array($project1->project_id, $resultIds2));
        $this->assertFalse(in_array($project2->project_id, $resultIds2));
        
        $this->assertFalse(in_array($project1->project_id, $resultIds3));
        $this->assertTrue(in_array($project2->project_id, $resultIds3));
        
        $this->assertFalse(in_array($project1->project_id, $resultIds4));
        $this->assertFalse(in_array($project2->project_id, $resultIds4));
        
        $this->assertTrue(in_array($project1->project_id, $resultIds5));
        $this->assertFalse(in_array($project2->project_id, $resultIds5));
        
        $this->assertFalse(in_array($project1->project_id, $resultIds6));
        $this->assertTrue(in_array($project2->project_id, $resultIds6));
        
        $this->assertTrue(in_array($project1->project_id, $resultIds7));
        $this->assertTrue(in_array($project2->project_id, $resultIds7));
        
        $this->assertFalse(in_array($project1->project_id, $resultIds8));
        $this->assertFalse(in_array($project2->project_id, $resultIds8));
    }
}