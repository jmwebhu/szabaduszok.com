<?php

/**
 * Class Controller_Project
 *
 * Felelosseg: Projekt controllerek ososztalya, altalanos viselkedessel
 */

abstract class Controller_Project extends Controller_DefaultTemplate
{
    /**
     * @var Entity_Project
     */
    protected $_project;
    protected $_error = false;

    /**
     * @param Entity_Project $project
     */
    public function setProject(Entity_Project $project)
    {
        $this->_project = $project;
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_project = new Entity_Project();
    }

    public function defaultFinally()
    {
        if ($this->request->method() == Request::POST) {
            $this->handlePostFinally();
        }
    }

    protected function handlePostFinally()
    {
        Model_Database::trans_end([!$this->_error]);

        if (!$this->_error) {
            $this->redirectToProfile();
        }
    }

    protected function redirectToProfile()
    {
        header('Location: ' . Route::url('projectProfile', ['slug' => $this->_project->getSlug()]), true, 302);
        die();
    }
}
