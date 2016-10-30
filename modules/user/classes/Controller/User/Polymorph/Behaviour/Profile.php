<?php

interface Controller_User_Polymorph_Behaviour_Profile
{
    /**
     * @return int
     */
    public function getUserType();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return Model_User
     */
    public function getUserModel();
}