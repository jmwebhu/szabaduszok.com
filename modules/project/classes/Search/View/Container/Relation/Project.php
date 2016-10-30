<?php

class Search_View_Container_Relation_Project extends Search_View_Container_Relation
{
    /**
     * @return string
     */
    public function getIndustrySubtitle()
    {
        return "Mutasd azokat a projekteket, amik az alábbi <span class=\"bold\">iparágakba</span> tartoznak:";
    }

    /**
     * @return string
     */
    public function getProfessionSubtitle()
    {
        return "ÉS a következő <span class=\"bold\">szakterületeken</span> futnak:";
    }

    /**
     * @return string
     */
    public function getSkillSubtitle()
    {
        return "ÉS az alábbi <span class=\"bold\">képességek:</span>";
    }
}