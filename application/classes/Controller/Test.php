<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $array = [
            'skill_id' => null,
            'name' => null,
            'slug' => null
        ];

        echo Debug::vars(array_key_exists('skill_id', $array));
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
