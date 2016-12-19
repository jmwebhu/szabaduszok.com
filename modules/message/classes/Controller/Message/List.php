<?php

class Controller_Message_List extends Controller_DefaultTemplate
{
    public function action_index()
    {
        try {
            $user                   = Auth::instance()->get_user();
            $this->context->title   = 'Üzenetek ' . $user->name();
            $model                  = ORM::factory('Conversation')->where('slug', '=', $this->request->param('slug'))->limit(1)->find();

            $this->context->conversationUrl = new Entity_Conversation($model);
            if ($this->context->conversationUrl->loaded()) {
                $auth = new Authorization_Conversation($this->context->conversationUrl->getModel());

                $this->throwForbiddenExceptionIfNot(
                    $auth->canView(), 'Nincs jogosultságod a beszélgetés megtekintéséhez');

                $this->context->messages        = Business_Message::groupGivenMessagesByTextifiedDays(
                    $this->context->conversationUrl->getMessagesBy($user->user_id));
            }

            $this->context->conversations   = Entity_Conversation::getForLeftPanelBy($user->user_id);

            $user = new Model_User;
            //$allUser  = $user->getAll();
            $users = [];

            $users = AB::select()->from($user)->where('user_id', '!=', Auth::instance()->get_user()->user_id)->order_by('lastname')->execute()->as_array();

            /*foreach ($allUser as $oneUser) {
                if ($oneUser->user_id != Auth::instance()->get_user()->user_id) {
                    $users[] = $oneUser;
                }
            }*/

            $this->context->users = $users;

        } catch (HTTP_Exception_403 $exforbidden) {
            $exforbidden->setRedirectRoute($this->request->route());
            $exforbidden->setRedirectSlug($this->request->param('slug'));
            
            Session::instance()->set('error', $exforbidden->getMessage());
            $this->defaultExceptionRedirect($exforbidden);
        }
    }
}