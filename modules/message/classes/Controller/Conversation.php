<?php 

class Controller_Conversation extends Controller
{
    public function action_contact()
    {
        $this->auto_render  = false;

        $slug               = $this->request->param('userslug');
        $user               = new Model_User();
        $user               = $user->getBySlug($slug);
        $conversation       = Entity_Conversation::getConversationBetween(
            [$user->user_id, Auth::instance()->get_user()->user_id]);

        header('Location: ' . Route::url('messagesList', ['slug' => $conversation->getSlug()]), true, 302);
        die();
    }
}