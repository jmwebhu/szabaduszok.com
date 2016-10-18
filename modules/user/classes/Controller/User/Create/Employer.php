<?php

class Controller_User_Create_Employer extends Controller_User_Create
{
    /**
     * @return int
     */
    protected function getUserType()
    {
        return Entity_User::TYPE_EMPLOYER;
    }

    /**
     * @return string
     */
    protected function getProfileUrl()
    {
        return Route::url('projectOwnerProfile', ['slug' => $this->_user->getSlug()]);
    }

    protected function handleSignup()
    {
        $signup         = new Model_Signup();
        $signup->createIfHasEmail($this->_email, 2);
    }

    protected function handlePostRequest()
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_start();

            $this->_user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
            $this->_user->submit(Input::post_all());

            $this->_error = false;

            Auth_ORM::instance()->force_login($this->_user->getModel());
        }
    }
}