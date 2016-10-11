<?php

class Search_View_Container_Relation_Item_Project extends Search_View_Container_Relation_Item
{
    /**
     * @return string
     */
    public function getSubtitle()
    {
        switch ($this->_type) {
            case self::TYPE_INDUSTRY:
                return "Mutasd azokat a projekteket, amik az alábbi <span class=\"bold\">iparágakba</span> tartoznak:";
                break;

            case self::TYPE_PROFESSION:
                return "ÉS a következő <span class=\"bold\">szakterületeken</span> futnak:";
                break;

            case self::TYPE_SKILL:
                return "ÉS az alábbi <span class=\"bold\">képességek:</span>";
                break;
        }
    }
}