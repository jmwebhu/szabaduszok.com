<?php 

class Controller_Message extends Controller
{
    public function action_contact()
    {
        $this->auto_render = false;
        $slug = $this->request->param('userslug');
        $user = new Entity_User();
        $user = $user->getBySlug($slug);

    }
    
}