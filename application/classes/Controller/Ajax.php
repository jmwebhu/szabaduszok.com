<?php

class Controller_Ajax extends Controller_DefaultTemplate
{
    /**
     * @var string
     */
    protected $_jsonResponse;

    /**
     * @var bool
     */
    protected $_error = false;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->throwExceptionIfNotAjax();
        $this->auto_render = false;
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
     * @throws Exception
     */
    protected function throwExceptionIfErrorInResponse()
    {
        $response = json_decode($this->_jsonResponse, true);
        if (Arr::get($response, 'error', false) == true) {
            throw new Exception(Arr::get($response, 'message'));
        }
    }

    /**
     * @param Exception $ex
     */
    protected function handleException(Exception $ex, $response = null)
    {
        Log::instance()->addException($ex);

        $this->_error           = true;
        $this->_jsonResponse    = json_encode(['error' => ($response) ? $response : $this->_error]);

        $this->response->status(500);
    }

    protected function callMethod()
    {
        $method = $this->request->param('actiontarget');
        $this->throwExceptionIfMethodNotExists($method);

        $this->{$method}();
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
}