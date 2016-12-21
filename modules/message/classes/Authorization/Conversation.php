<?php

class Authorization_Conversation extends Authorization
{
    /**
     * @return bool
     */
    public function canView()
    {
        return $this->isIn();
    }
    
    /**
     * @return bool
     */
    public function canSendMessage()
    {
        return $this->isIn();   
    }

    public function canContact()
    {
        return ($this->_model->user_id != $this->_user->user_id);
    }
    
    /**
     * @return bool
     */
    protected function isIn()
    {
        return $this->_model->has('users', $this->_user);
    }
    
}