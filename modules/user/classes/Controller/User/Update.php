<?php

abstract class Controller_User_Update extends Controller_DefaultTemplate
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }
}