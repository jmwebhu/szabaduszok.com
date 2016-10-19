<?php

class Controller_User_Ajax extends Controller_Ajax
{
    public function action_index()
    {
        try {
            Model_Database::trans_start();
            $this->callMethod();

        } catch (Exception $ex) {
            $this->handleException($ex);

        } finally {
            Model_Database::trans_end([!$this->_error]);
        }

        $this->response->body($this->_jsonResponse);
    }

    protected function rate()
    {
        $user = AB::select()->from(new Model_Project())->where('user_id', '=', Input::post('user_id'))->limit(1)->execute()->current();
        $this->_jsonResponse = json_encode($user->rate(Input::post('rating')));
    }

    protected function saveProjectNotification()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_FREELANCER, Auth::instance()->get_user()->user_id);
        $this->_jsonResponse = json_encode($user->saveProjectNotification(Input::post_all()));
    }
}