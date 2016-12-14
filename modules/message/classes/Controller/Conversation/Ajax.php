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
}