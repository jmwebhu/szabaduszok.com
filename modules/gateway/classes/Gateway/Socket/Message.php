<?php

class Gateway_Socket_Message extends Gateway_Socket
{    
    /**
     * @return string
     */
    protected function getUri()
    {
        return 'message';
    }   
}