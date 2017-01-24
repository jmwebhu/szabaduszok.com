<?php

class Controller_Message_Ajax extends Controller_Ajax
{
    public function action_index()
    {
        try {
            Model_Database::trans_start();
            $this->callMethod();
            $this->throwExceptionIfErrorInResponse();

        } catch (HTTP_Exception_403 $ex) {
            $this->handleException($ex, $ex->getMessage());

        } catch (Exception $ex) {
            $this->handleException($ex);

        } finally {
            Model_Database::trans_end([!$this->_error]);
        }

        $this->response->body($this->_jsonResponse);
    }

    protected function send()
    {
        $message            = Input::post('message');
        $conversation_id    = Input::post('conversation_id');
        $sender_id          = Auth::instance()->get_user()->user_id;
        $auth               = new Authorization_Conversation(new Model_Conversation($conversation_id));

        $this->throwForbiddenExceptionIfNot($auth->canSendMessage(), 'Unauthorized');

        $messageEntity      = new Entity_Message();
        $result             = $messageEntity->send(
            compact('message', 'conversation_id', 'sender_id')
        );
        
        $this->_jsonResponse    = json_encode($result);
    }

    protected function flagAsRead()
    {        
        $conversation           = new Entity_Conversation(Input::post('id'));
        $this->_jsonResponse    = json_encode($conversation->flagMessagesAsRead());
    }
    
}