<?php

class Project_Notification_Search_View_Container_Relation_User extends Search_View_Container_Relation
{
    /**
     * @return string
     */
    public function getIndustrySubtitle()
    {
        return "Csak azokról a projektekről szeretnék értesítést kapni, amik az alábbi <strong>iparágakba</strong> tartoznak:";
    }

    /**
     * @return string
     */
    public function getProfessionSubtitle()
    {
        return "ÉS a következő <strong>szakterületeken</strong> futnak:";
    }

    /**
     * @return string
     */
    public function getSkillSubtitle()
    {
        return "ÉS az alábbi <strong>képességek</strong>:";
    }
}