<?php

class Entity_User_Freelancer extends Entity_User
{
    /**
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        parent::__construct();
        $this->_model       = new Model_User_Freelancer($id);
        $this->_file        = new File_User_Freelancer($this->_model);
        $this->_business    = new Business_User_Freelancer($this->_model);

        if ($id) {
            $this->mapModelToThis();
        }
    }

    /**
     * @param array $post
     * @return array
     */
    protected function fixPost(array $post)
    {
        $data = parent::fixPost($post);
        return Text_User::fixUrl($data, 'webpage');
    }
}