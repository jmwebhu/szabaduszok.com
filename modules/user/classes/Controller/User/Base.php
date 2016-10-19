<?php

abstract class Controller_User_Base extends Controller_DefaultTemplate
{
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
}