<?php

class Entity_User_Test extends Unittest_TestCase
{
    private static $_db;

    private static $_insertedUserIds = [];

    public static $_industries = [];
    public static $_professions = [];
    public static $_skills = [];

    /**
     * @covers Entity_User::getName()
     */
    public function testGetName()
    {
        $user = new Entity_User_Employer();
        $user->setLastname('Joó');
        $user->setFirstname('Martin');

        $this->assertEquals('Joó Martin', $user->getName());
    }

    /**
     * @covers Entity_User::unsetPasswordFrom()
     */
    public function testUnsetPasswordFromIdExistsPasswordExists()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $post = [
            'user_id' => 1,
            'password'  => 'asdf'
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertEquals('asdf', $data['password']);
    }

    /**
     * @covers Entity_User::unsetPasswordFrom()
     */
    public function testUnsetPasswordFromIdExistsPasswordNotExists()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $post = [
            'user_id' => 1,
            'password'  => ''
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));

        $post = [
            'user_id' => 1,
            'password'  => null
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));

        $post = [
            'user_id' => 1
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));
    }

    /**
     * @covers Entity_User::unsetPasswordFrom()
     */
    public function testUnsetPasswordFromIdNotExistsPasswordExists()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $post = [
            'password'  => 'asdf'
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);
        $this->assertEquals('asdf', $data['password']);

        $post = [
            'password'  => 'asdf',
            'user_id'   => ''
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);
        $this->assertEquals('asdf', $data['password']);

        $post = [
            'password'  => 'asdf',
            'user_id'   => null
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);
        $this->assertEquals('asdf', $data['password']);
    }

    /**
     * @covers Entity_User::unsetPasswordFrom()
     */
    public function testUnsetPasswordFromIdNotExistsPasswordNotExists()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $post = [];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));

        $post = [
            'user_id'   => '',
            'passrod'   => ''
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));

        $post = [
            'user_id'   => null,
            'passrod'   => null
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));
    }

    /**
     * @covers Entity_User::createUser()
     */
    public function testCreateUserOk()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $this->assertTrue($user instanceof Entity_User_Freelancer);

        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $this->assertTrue($user instanceof Entity_User_Employer);
    }

    /**
     * @covers Entity_User::createUser()
     * @expectedException Exception
     */
    public function testCreateUserNotOk()
    {
        Entity_User::createUser(9912);
    }

    /**
     * @covers Entity_User::submit()
     */
    public function testSubmitEmployerWithoutRelations()
    {
        $employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $data = [
            'is_company'            => 'on',
            'company_name'          => 'Szabaduszok.com Zrt.',
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => 'joomartin@jmweb.hu',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás'
        ];

        $employer->submit($data);

        self::$_insertedUserIds[] = $employer->getUserId();

        $this->assertUserIdExistsInDatabase($employer->getUserId());
        $this->assertUserIdExistsInSession($employer->getUserId());
        $this->assertUserIdExistsInCache($employer->getUserId());
        $this->assertEmailNotExistsInSignup($employer->getEmail());
        $this->assertEquals(1, $employer->getIsCompany());
        $this->assertEquals('Szabaduszok.com Zrt.', $employer->getCompanyName());
        $this->assertEquals(Auth::instance()->hash('Password123'), $employer->getPassword());
        $this->assertEquals('joo-martin', $employer->getSlug());
        $this->assertNotEmpty($employer->getSearchText());
    }

    /**
     * @covers Entity_User::submit()
     */
    public function testSubmitEmployerWithRelations()
    {
        $employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $data = [
            'is_company'            => '',
            'company_name'          => '',
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => 'joomartin@jmweb.hu',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás',
            'industries'            => [1],
            'professions'           => [2, 3]
        ];

        $employer->submit($data);

        self::$_insertedUserIds[] = $employer->getUserId();

        $this->assertUserIdExistsInDatabase($employer->getUserId());
        $this->assertUserIdExistsInSession($employer->getUserId());
        $this->assertUserIdExistsInCache($employer->getUserId());
        $this->assertEmailNotExistsInSignup($employer->getEmail());
        $this->assertUserRelationContainsIds('industry', [1], $employer->getUserId());
        $this->assertUserRelationContainsIds('profession', [2, 3], $employer->getUserId());
        $this->assertEquals(0, $employer->getIsCompany());
        $this->assertEquals('', $employer->getCompanyName());
        $this->assertEquals(Auth::instance()->hash('Password123'), $employer->getPassword());
        $this->assertEquals('joo-martin', $employer->getSlug());
    }

    /**
     * @covers Entity_User::submit()
     */
    public function testSubmitFreelancerWithoutRelations()
    {
        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $data = [
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => 'joomartin@szabaduszok.com',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás',
            'min_net_hourly_wage'   => '2500',
            'webpage'               => 'szabaduszok.com'
        ];

        $freelancer->submit($data);

        self::$_insertedUserIds[] = $freelancer->getUserId();

        $this->assertUserIdExistsInDatabase($freelancer->getUserId());
        $this->assertUserIdExistsInSession($freelancer->getUserId());
        $this->assertUserIdExistsInCache($freelancer->getUserId());
        $this->assertEmailNotExistsInSignup($freelancer->getEmail());
        $this->assertEquals(Auth::instance()->hash('Password123'), $freelancer->getPassword());
        $this->assertEquals('joo-martin', $freelancer->getSlug());
        $this->assertEquals('http://szabaduszok.com', $freelancer->getWebpage());
        $this->assertEquals('2500', $freelancer->getMinNetHourlyWage());
        $this->assertEquals('1', $freelancer->getSkillRelation());
        $this->assertEquals('1', $freelancer->getNeedProjectNotification());
    }

    /**
     * @covers Entity_User::submit()
     */
    public function testSubmitFreelancerWithRelations()
    {
        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $data = [
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => 'joomartin@szabaduszok.com',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás',
            'min_net_hourly_wage'   => '2500',
            'webpage'               => 'szabaduszok.com',
            'industries'            => [1],
            'professions'           => [1, 2, 3],
            'skills'                => [3, 4, 6, 7, 8, 9]
        ];

        $freelancer->submit($data);

        self::$_insertedUserIds[] = $freelancer->getUserId();

        $this->assertUserIdExistsInDatabase($freelancer->getUserId());
        $this->assertUserIdExistsInSession($freelancer->getUserId());
        $this->assertUserIdExistsInCache($freelancer->getUserId());
        $this->assertEmailNotExistsInSignup($freelancer->getEmail());
        $this->assertEquals(Auth::instance()->hash('Password123'), $freelancer->getPassword());
        $this->assertEquals('joo-martin', $freelancer->getSlug());
        $this->assertEquals('http://szabaduszok.com', $freelancer->getWebpage());
        $this->assertEquals('2500', $freelancer->getMinNetHourlyWage());
        $this->assertEquals('1', $freelancer->getSkillRelation());
        $this->assertEquals('1', $freelancer->getNeedProjectNotification());

        $this->assertUserRelationContainsIds('industry', [1], $freelancer->getUserId());
        $this->assertUserRelationContainsIds('profession', [1, 2, 3], $freelancer->getUserId());
        $this->assertUserRelationContainsIds('skill', [3, 4, 6, 7, 8, 9], $freelancer->getUserId());

        /**
         * projekt ertesitok
         */
    }

    public function assertUserIdExistsInDatabase($id)
    {
        $user = DB::select()->from('users')->where('user_id', '=', $id)->execute()->current();
        $this->assertEquals($id, Arr::get($user, 'user_id'));
    }

    public function assertUserIdExistsInSession($id)
    {
        $sessionUser = Session::instance()->get('auth_user');
        $this->assertTrue($sessionUser->loaded());
        $this->assertEquals($id, $sessionUser->user_id);
    }

    public function assertUserIdExistsInCache($id)
    {
        $cacheUsers = Cache::instance()->get('users');
        $cacheUser = Arr::get($cacheUsers, $id);

        $this->assertTrue($cacheUser->loaded());
        $this->assertEquals($id, $cacheUser->user_id);
    }

    public function assertEmailNotExistsInSignup($email)
    {
        $signup = DB::select()->from('signups')->where('email', '=', $email)->execute()->current();
        $this->assertEmpty($signup);
    }

    public function assertUserRelationContainsIds($relationSingularName, array $relationIds, $userId)
    {
        $class = 'Model_' . ucfirst($relationSingularName);
        $model = new $class();

        $relations = DB::select()
            ->from('users_' . $model->object_plural())
            ->where('user_id', '=', $userId)
            ->and_where($model->primary_key(), 'IN', $relationIds)
            ->execute()->as_array();

        $this->assertEquals(count($relationIds), count($relations));

        foreach ($relations as $relation) {
            $this->assertTrue(in_array($relation[$model->primary_key()], $relationIds));
        }
    }

    public function setUp()
    {
        self::truncateUsers();
        self::truncateRelations();
        self::initRelation('industry', 3);
        self::initRelation('profession', 5);
        self::initRelation('skill', 10);
    }

    public function tearDown()
    {
        self::truncateUsers();
        self::truncateRelations();
    }

    protected static function truncateUsers()
    {
        DB::delete('users')->execute();
        Cache::instance()->set('users', []);
    }

    protected static function truncateRelations()
    {
        DB::delete('industries')->execute();
        DB::delete('professions')->execute();
        DB::delete('skills')->execute();

        Cache::instance()->set('industries', []);
        Cache::instance()->set('professions', []);
        Cache::instance()->set('skills', []);
    }

    /**
     * @param string $relation
     * @param int $count
     */
    protected static function initRelation($relation, $count)
    {
        $class = 'Model_' . ucfirst($relation);

        for ($i = 0; $i < $count; $i++) {
            $relationModel = new $class();

            $relationModel->{$relationModel->primary_key()} = $i + 1;
            $relationModel->name = $relation . '-' . ($i + 1);

            $relationModel->save();

            $classReflection = new ReflectionClass('Entity_User_Test');
            $ids = $classReflection->getStaticPropertyValue('_' . $relationModel->object_plural());
            $ids[] = $i + 1;

            $classReflection->setStaticPropertyValue('_' . $relationModel->object_plural(), $ids);
        }
    }
}