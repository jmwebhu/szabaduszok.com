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
        $partner = new Model_Project_Partner();
        $this->_jsonResponse = json_encode($partner->apply(Input::post_all()));
    }

    protected function undoApplication()
    {
        $partner = new Model_Project_Partner(Input::post('project_partner_id'));
        $this->_jsonResponse = json_encode($partner->undoApplication());
    }

    protected function approveApplication()
    {
        $partner = new Model_Project_Partner(Input::post('project_partner_id'));
        $this->_jsonResponse = json_encode($partner->approveApplication());
    }

    protected function rejectApplication()
    {
        $partner = new Model_Project_Partner(Input::post('project_partner_id'));
        $this->_jsonResponse = json_encode($partner->rejectApplication());
    }

    protected function cancelParticipation()
    {
        $partner = new Model_Project_Partner(Input::post('project_partner_id'));
        $this->_jsonResponse = json_encode($partner->cancelParticipation());
    }
}