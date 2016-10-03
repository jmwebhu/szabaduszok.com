<?php

class Controller_Project_Ajax extends Controller_DefaultTemplate
{
    private $_jsonResponse;
    private $_error = false;

    public function __construct(Request $request, Response $response)
    {
        if (!$request->is_ajax()) {
            throw new HTTP_Exception_400('Only Ajax');
        }

        $this->auto_render = false;
        parent::__construct($request, $response);
    }

    public function action_index()
    {
        try {
            Model_Database::trans_start();
            $this->switchActiontarget();

        } catch (Exception $ex) {
            Log::instance()->addException($ex);

            $this->_error           = true;
            $this->_jsonResponse    = json_encode(['error' => $this->_error]);

            $this->response->status(500);

        } finally {
            Model_Database::trans_end([!$this->_error]);
        }

        $this->response->body($this->_jsonResponse);
    }

    protected function switchActiontarget()
    {
        switch ($this->request->param('actiontarget')) {
            case 'inactivate':
                $this->inactivate();
                break;

            case 'professionAutocomplete':
                $this->professionAutocomplete();
                break;

            case 'skillAutocomplete':
                $this->skillAutocomplete();
                break;

            default:
                throw new HTTP_Exception_400('Action target not found');
        }
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