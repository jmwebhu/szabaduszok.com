<?php

class Viewhelper_Message
{
    /**
     * @var Entity_Message
     */
    protected $_message;

    /**
     * @var int
     */
    protected $_userId;

    /**
     * @var Viewhelper_Message_Type
     */
    protected $_type;
    

    /**
     * @param Entity_Message   $_message   
     * @param int   $_userId   
     */
    public function __construct($_message, $_userId)
    {
        $this->_message = $_message;
        $this->_userId = $_userId;
    }
}