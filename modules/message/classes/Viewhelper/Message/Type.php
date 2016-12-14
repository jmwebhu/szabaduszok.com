<?php

abstract class Viewhelper_Message_Type
{
    const TYPE_INCOMING = 'incoming';
    const TYPE_OUTGOING = 'outgoing';

    const COLOR_INCOMING = 'gray';
    const COLOR_OUTGOING = 'blue';

    /**
     * @var Entity_Message
     */
    protected $_message;
    
    /**
     * @var int
     */
    protected $_userId;

    /**
     * Class Constructor
     * @param Entity_Message   $_message   
     * @param int   $_userId   
     */
    public function __construct(Entity_Message $_message, $_userId)
    {
        $this->_message = $_message;
        $this->_userId  = $_userId;
    }
    
    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return string
     */
    abstract public function getColor();
}