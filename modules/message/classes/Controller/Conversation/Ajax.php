<?php

class Controller_Conversation_Ajax extends Controller_Ajax
{
    public function action_index()
    {
        try {
            Model_Database::trans_start();
            $this->callMethod();
            $this->throwExceptionIfErrorInResponse();

        } catch (Exception $ex) {
            $this->handleException($ex);

        } finally {
            Model_Database::trans_end([!$this->_error]);
        }

        $this->response->body($this->_jsonResponse);
    }

    protected function getMessages()
    {
        $conversation           = new Entity_Conversation(Input::post('id'));
        $messages = $conversation->getMessagesBy(Auth::instance()->get_user()->user_id);
        $result = [];

        foreach ($messages as $message) {
            $result[] = $message->object();
        }
        
        $this->_jsonResponse    = json_encode($result);
    }
}