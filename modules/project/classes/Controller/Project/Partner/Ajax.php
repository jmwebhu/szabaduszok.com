<?php

class Controller_Project_Partner_Ajax extends Controller_Ajax
{
    public function action_index()
    {
        try {
            Model_Database::trans_start();
            $this->callMethod();
            $this->throwExceptionIfErrorInResponse();

        } catch (Exception $ex) {
            $this->handleException($ex);

        } finally {
            Model_Database::trans_end([!$this->_error]);
        }

        $this->response->body($this->_jsonResponse);
    }

    protected function apply()
    {
        $partner        = new Model_Project_Partner();
        $data           = Input::post_all();
        $user           = new Model_User(Arr::get($data, 'user_id'));
        $project        = AB::select()->from(new Model_Project())->where('project_id', '=', Arr::get($data, 'project_id'))->execute()->current();
        $authorization  = new Authorization_User($project, $user);

        $this->_jsonResponse = json_encode($partner->apply(Input::post_all(), $authorization));
    }

    protected function undoApplication()
    {
        $partner                = new Model_Project_Partner(Input::post('project_partner_id'));
        $user                   = new Model_User($partner->user_id);
        $authorization          = new Authorization_User($partner->project, $user);

        $this->_jsonResponse    = json_encode($partner->undoApplication($authorization, Input::post('extra_data', [])));
    }

    protected function approveApplication()
    {
        $partner        = new Model_Project_Partner(Input::post('project_partner_id'));
        $userId         = (Input::post('user_id')) ? Input::post('user_id') : Auth::instance()->get_user()->user_id;
        $user           = new Model_User($userId);
        $authorization  = new Authorization_User($partner->project, $user);

        $this->_jsonResponse = json_encode($partner->approveApplication($authorization, Input::post('extra_data', [])));
    }

    protected function rejectApplication()
    {
        $partner        = new Model_Project_Partner(Input::post('project_partner_id'));
        $userId         = (Input::post('user_id')) ? Input::post('user_id') : Auth::instance()->get_user()->user_id;
        $user           = new Model_User($userId);
        $authorization  = new Authorization_User($partner->project, $user);

        $this->_jsonResponse = json_encode($partner->rejectApplication($authorization, Input::post('extra_data', [])));
    }

    protected function cancelParticipation()
    {
        $partner        = new Model_Project_Partner(Input::post('project_partner_id'));
        $user           = new Model_User(Auth::instance()->get_user()->user_id);
        $authorization  = new Authorization_User($partner->project, $user);

        $this->_jsonResponse = json_encode($partner->cancelParticipation($authorization, Input::post('extra_data', [])));
    }
}