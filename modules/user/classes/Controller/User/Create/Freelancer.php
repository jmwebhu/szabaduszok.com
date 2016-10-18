<?php

class Controller_User_Create_Freelancer extends Controller_User_Create
{
    /**
     * @return int
     */
    protected function getUserType()
    {
        return Entity_User::TYPE_FREELANCER;
    }

    /**
     * @return string
     */
    protected function getProfileUrl()
    {
        return Route::url('freelancerProfile', ['slug' => $this->_user->getSlug()]);
    }

    protected function setContext()
    {
        parent::setContext();

        $profile					= new Model_Profile();
        $this->context->profiles    = $profile->where('is_active', '=', 1)->find_all();
    }

    protected function handleSignup()
    {
        $signup         = new Model_Signup();
        $signup->createIfHasEmail($this->_email);
    }

    protected function handlePostRequest()
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_start();

            $this->_user = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
            $this->_user->submit(Input::post_all());

            $this->_error = false;

            Auth_ORM::instance()->force_login($this->_user->getModel());
        }
    }
}