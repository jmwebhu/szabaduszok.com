<?php

abstract class Controller_User_Write extends Controller_DefaultTemplate
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
     * @return int
     */
    abstract protected function getUserType();

    /**
     * @return string
     */
    abstract protected function getProfileUrl();

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->_error       = false;
        $this->_user        = Entity_User::createUser($this->getUserType());
        $this->_viewhelper  = Viewhelper_User_Factory::createViewhelper($this->_user, Viewhelper_User::ACTION_EDIT);
    }
}