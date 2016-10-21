<?php

abstract class Controller_User extends Controller_DefaultTemplate
{
    /**
     * @var bool
     */
    protected $_error;

    /**
     * @var Entity_User
     */
    protected $_user;

    /**
     * @var Viewhelper_User
     */
    protected $_viewhelper;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_error       = false;
    }

    /**
     * @param bool $expression
     * @throws HTTP_Exception_404
     */
    protected function throwNotFoundExceptionIfNot($expression)
    {
        if (!$expression) {
            throw new HTTP_Exception_404('Sajnáljuk, de nincs ilyen felhasználó');
        }
    }

    /**
     * @param bool $expression
     * @param string $message
     * @throws HTTP_Exception_403
     */
    protected function throwForbiddenExceptionIfNot($expression, $message = 'Nincs jogosultságod az odal megtekintéséhez')
    {
        if (!$expression) {
            throw new HTTP_Exception_403($message);
        }
    }

    /**
     * @param string $contextError
     */
    protected function handleSessionError($contextError = 'session_error')
    {
        if (Session::instance()->get('error')) {
            $this->context->{$contextError} = Session::instance()->get('error');
            Session::instance()->delete('error');
        }
    }
}