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
        $conversation           = new Entity_Conversation(Input::get('id'));
        $data = Business_Message::groupGivenMessagesByTextifiedDays(
            $conversation->getMessagesBy(Auth::instance()->get_user()->user_id));

        $result = ['messages' => [], 'conversation' => $conversation->object()];

        foreach ($data as $day => $messages) {
            foreach ($messages as $i => $message) {
                $index = $message->getCreatedAtForView();

                $result['messages'] = Arr::setKey($result['messages'], $index, []);
                $result['messages'][$index][$i] = $message->object();   

                $result['messages'][$index][$i]['type'] = $message->getType();   
                $result['messages'][$index][$i]['color'] = $message->getColor();   
            }            
        }
        
        $this->_jsonResponse    = json_encode($result);
    }

    protected function flagAsRead()
    {
        $conversation           = new Entity_Conversation(Input::post('id'));
        $result                 = $conversation->flagMessagesAsRead();

        if (Input::post('isActiveConversation') == '0') {
            $socket                 = new Gateway_Socket_Message;
            $socket->signalUpdateCount(Auth::instance()->get_user()->user_id);
        }

        $this->_jsonResponse    = json_encode($result);
    }
    
}