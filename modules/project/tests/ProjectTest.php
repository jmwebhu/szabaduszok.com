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
}