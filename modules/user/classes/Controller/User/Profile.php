<?php

abstract class Controller_User_Profile extends Controller_User_Base
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
     * @return int
     */
    abstract public function getUserType();

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_error = false;
    }
}