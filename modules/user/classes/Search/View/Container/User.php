<?php

class Search_View_Container_User extends Search_View_Container
{
    /**
     * @return string
     */
    public function getSimpleSubtitle()
    {
        return "Mutasd azokat a Szabadúszókat, akiknek a profiljábam megtalálható az alábbi <span class=\"bold\">kifejezés</span>:";
    }

    /**
     * @return string
     */
    public function getHeadingText()
    {
        return 'Szabadúszó kereső';
    }

    /**
     * @return string
     */
    public function getComplexFormAction()
    {
        return Route::url('freelancers');
    }

    public function getSimpleFormAction()
    {
        return $this->getComplexFormAction();
    }
}