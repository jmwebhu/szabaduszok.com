<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $content = Twig::getHtmlFromTemplate('Templates/project_new.html.twig', []);
        var_dump($content);
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
