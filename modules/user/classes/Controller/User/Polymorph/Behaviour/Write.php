<?php

interface Controller_User_Polymorph_Behaviour_Write
{
    /**
     * @return int
     */
    public function getUserType();

    /**
     * @return int
     */
    public function getProfileUrl();
}