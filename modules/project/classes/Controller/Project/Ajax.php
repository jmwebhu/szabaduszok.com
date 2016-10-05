<?php

/**
 * Class Controller_Project_Ajax
 *
 * Felelosseg: Ajax keresek kiszolgalasa
 */

class Controller_Project_Ajax extends Controller_Project
{
    private $_jsonResponse;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->throwExceptionIfNotAjax();
        $this->auto_render = false;
    }

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

    /**
     * @throws HTTP_Exception_400
     */
    protected function throwExceptionIfNotAjax()
    {
        if (!$this->request->is_ajax()) {
            throw new HTTP_Exception_400('Only Ajax');
        }
    }

    /**
     * @param Exception $ex
     */
    protected function handleException(Exception $ex)
    {
        Log::instance()->addException($ex);

        $this->_error           = true;
        $this->_jsonResponse    = json_encode(['error' => $this->_error]);

        $this->response->status(500);
    }

    protected function callMethod()
    {
        $method = $this->request->param('actiontarget');
        $this->throwExceptionIfMethodNotExists($method);

        $this->{$method};
    }

    /**
     * @param string $method
     * @throws HTTP_Exception_400
     */
    protected function throwExceptionIfMethodNotExists($method)
    {
        if (!method_exists($this, $method)) {
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