<?php

class Entity_User_Employer extends Entity_User
{
    /**
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        parent::__construct();
        $this->_model       = new Model_User_Employer($id);
        $this->_file        = new File_User_Employer($this->_model);
        $this->_business    = new Business_User_Employer($this->_model);

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
        return Text_User::alterCheckboxValue($data);
    }

}