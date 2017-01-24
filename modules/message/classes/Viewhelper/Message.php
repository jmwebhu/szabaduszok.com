<?php

abstract class Viewhelper_Message
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

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        $textify = Date::textifyDay($this->_message->getCreatedAt()->format('Y-m-d'));

        if (strpos($textify, '-') === false) {
            $createdAt = __($textify);
        } else {
            $createdAt = date('m.d', strtotime($textify));
        }

        return $createdAt;
    }
}