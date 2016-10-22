<?php

class Entity_User_Test extends Unittest_TestCase
{
    private static $_db;

    private static $_insertedIds = [];

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

    public function testSubmitWithoutRelations()
    {
        $employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);

        // FONTOS!
        $employer->getModel()->setDb('testing');

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

        self::$_insertedIds[] = $employer->getUserId();

        $this->thenUserIdShouldExistsInDatabase($employer->getUserId());
        $this->thenUserIdShouldExistsInSession($employer->getUserId());
        $this->thenUserIdShouldExistsInCache($employer->getUserId());
        $this->thenEmailShouldNotExistsInSignup($employer->getEmail());
    }

    public function thenUserIdShouldExistsInDatabase($id)
    {
        $user = DB::select()->from('users')->where('user_id', '=', $id)->execute(self::$_db)->current();
        $this->assertEquals($id, Arr::get($user, 'user_id'));
    }

    public function thenUserIdShouldExistsInSession($id)
    {
        $sessionUser = Session::instance()->get('auth_user');
        $this->assertTrue($sessionUser->loaded());
        $this->assertEquals($id, $sessionUser->user_id);
    }

    public function thenUserIdShouldExistsInCache($id)
    {
        $cacheUsers = Cache::instance()->get('users');
        $cacheUser = Arr::get($cacheUsers, $id);

        $this->assertTrue($cacheUser->loaded());
        $this->assertEquals($id, $cacheUser->user_id);
    }

    public function thenEmailShouldNotExistsInSignup($email)
    {
        $signup = DB::select()->from('signups')->where('email', '=', $email)->execute()->current();
        $this->assertEmpty($signup);
    }

    public static function setUpBeforeClass()
    {
        self::$_db = Database::instance('testing');
    }

    public static function tearDownAfterClass()
    {
        $users = Cache::instance()->get('users');
        foreach (self::$_insertedIds as $insertedId) {
            DB::delete('users')->where('user_id', '=', $insertedId)->execute(self::$_db);
            unset($users[$insertedId]);
        }

        Cache::instance()->set('users', $users);
    }
}