<?php

class Search_View_Container_Project extends Search_View_Container
{
    /**
     * @return string
     */
    public function getSimpleSubtitle()
    {
        return "Mutasd azokat a projekteket, amikben megtalálható az alábbi <span class=\"bold\">kifejezés</span>:";
    }

    /**
     * @return string
     */
    public function getEntityNameForHuman()
    {
        return 'Projekt';
    }
}