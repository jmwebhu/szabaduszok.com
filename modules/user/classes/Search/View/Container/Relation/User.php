<?php

class Search_View_Container_Relation_User extends Search_View_Container_Relation
{
    /**
     * @return string
     */
    public function getIndustrySubtitle()
    {
        return "Mutasd azokat a Szabadúszókat, akik az alábbi <span class=\"bold\">iparágakban</span> dolgoznak:";
    }

    /**
     * @return string
     */
    public function getProfessionSubtitle()
    {
        return "ÉS a következő <span class=\"bold\">szakterületekhez</span> értenek:";
    }

    /**
     * @return string
     */
    public function getSkillSubtitle()
    {
        return "ÉS az alábbi <span class=\"bold\">képességek:</span>";
    }
}