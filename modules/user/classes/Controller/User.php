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
     * @param string $contextError
     */
    protected function handleSessionError($contextError = 'session_error')
    {
        if (Session::instance()->get('error')) {
            $this->context->{$contextError} = Session::instance()->get('error');
            Session::instance()->delete('error');
        }
    }

    /**
     * @param string $location
     */
    protected function finallyAddToMailingListAndRedirecr($location)
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_end([!$this->_error]);

            if (!$this->_error) {
                $id             = Arr::get(Input::post_all(), 'user_id', false);
                $mailinglist    = Gateway_Mailinglist_Factory::createMailinglist($this->_user);
                $mailinglist->add((bool)$id);

                header('Location: ' . $location, true, 302);
                die();
            }
        }
    }
}