<?php

class Gateway_Socket_Conversation extends Gateway_Socket
{
    /**
     * @var Conversation
     */
    protected $_conversation;

    /**
     * Class Constructor
     * @param Conversation   $_conversation   
     */
    public function __construct($_conversation)
    {
        $this->_conversation = $_conversation;
    }

    public function signal()
    {
        $participants = $this->_conversation->getParticipantsExcept([
            Auth::instance()->get_user()->user_id
        ]);

        foreach ($participants as $participant) {
            $this->signalOneParticipant($participant);
        }
    }

    /**
     * @return string
     */
    protected function getUri()
    {
        return 'conversation';
    }
}