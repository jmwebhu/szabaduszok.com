<?php

abstract class Controller_Project extends Controller_DefaultTemplate
{
    /**
     * @var Entity_Project
     */
    protected $_project;
    protected $_error = false;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_project = new Entity_Project();
    }

    public function defaultFinally()
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_end([!$this->_error]);

            if (!$this->_error) {
                header('Location: ' . Route::url('projectProfile', ['slug' => $this->_project->getSlug()]), true, 302);
                die();
            }
        }
    }
}
