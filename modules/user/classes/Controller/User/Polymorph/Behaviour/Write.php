<?php

interface Controller_User_Polymorph_Behaviour_Write
{
    /**
     * @return int
     */
    public function getUserType();

    /**
     * @return string
     */
    public function getProfileUrl();

    /**
     * @return string
     */
    public function getUrl();
}