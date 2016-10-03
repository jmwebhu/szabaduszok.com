<?php

class Controller_Project extends Controller_DefaultTemplate
{
    private $_jsonResponse;

    /**
     * Projekt modositasa
     * 'Modositom' gomb megnyomasakor jelenik meg az oldal
     */


    public function action_ajax()
    {
        try {
            Model_Database::trans_start();
            $result = ['error' => false];

            if (!$this->request->is_ajax()) {
                throw new HTTP_Exception_400('Only Ajax');
            }

            $this->auto_render = false;

            switch ($this->request->param('actiontarget')) {
                case 'inactivate':
                    $project = new Model_Project(Input::post('id'));
                    $this->_jsonResponse = json_encode($project->inactivate());

                    break;

                case 'professionAutocomplete':
                    $profession = new Model_Profession();
                    $this->_jsonResponse = json_encode($profession->relationAutocomplete(Input::get('term')));

                    break;

                case 'skillAutocomplete':
                    $skill = new Model_Skill();
                    $this->_jsonResponse = json_encode($skill->relationAutocomplete(Input::get('term')));

                    break;

                default:
                    throw new HTTP_Exception_400('Action target not found');
            }
        } catch (Exception $ex) {
            Log::instance()->addException($ex);

            $result = ['error' => true];
            $this->_jsonResponse = json_encode($result);
            $this->response->status(500);
        } finally {
            Model_Database::trans_end([!Arr::get($result, 'error')]);
        }

        $this->response->body($this->_jsonResponse);
    }
}
