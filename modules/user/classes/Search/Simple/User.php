<?php

/**
 * Class Search_Simple_Project
 *
 * Felelosseg: Egyszeru kereses
 */

class Search_Simple_User extends Search_Simple
{
    /**
     * @return Model_User
     */
    public function createSearchModel()
    {
        return new Model_User();
    }

    /**
     * @return Array_Builder
     */
    public function getInitModels()
    {
        $withPicture	= AB::select()->from($this->createSearchModel())->where('profile_picture_path', '!=', '')->and_where('type', '=', 1)->order_by('lastname')->execute()->as_array();
        $withoutPicture	= AB::select()->from($this->createSearchModel())->where('profile_picture_path', '=', '')->and_where('type', '=', 1)->order_by('lastname')->execute()->as_array();
        $merged			= Arr::merge($withPicture, $withoutPicture);

        return AB::select()->from($merged)->where('user_id', '!=', '');
    }
}