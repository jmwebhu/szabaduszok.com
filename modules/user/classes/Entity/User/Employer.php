<?php

class Entity_User_Employer extends Entity_User
{
    /**
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id = null);
        $this->_file = new File_User_Employer($this->_model);
    }


    /**
     * @param array $post
     * @return array
     */
    protected function fixPost(array $post)
    {
        $data = parent::fixPost($post);
        return Text_User::alterCheckboxValue($data);
    }

}