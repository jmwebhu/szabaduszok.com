<?php

/**
 * Class Controller_Project_Ajax
 *
 * Felelosseg: Ajax keresek kiszolgalasa
 */

class Controller_Project_Ajax extends Controller_Ajax
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

    protected function inactivate()
    {
        $project = new Model_Project(Input::post('id'));
        $this->_jsonResponse = json_encode($project->inactivate());
    }

    protected function professionAutocomplete()
    {
        $profession = new Model_Profession();
        $this->_jsonResponse = json_encode($profession->relationAutocomplete(Input::get('term')));
    }

    protected function skillAutocomplete()
    {
        $skill = new Model_Skill();
        $this->_jsonResponse = json_encode($skill->relationAutocomplete(Input::get('term')));
    }
}