<?php

class Entity_User_Employer extends Entity_User
{
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