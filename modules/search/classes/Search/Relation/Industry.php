<?php

class Search_Relation_Industry extends Search_Relation
{
    /**
     * @return string
     */
    protected function getModelPk()
    {
        return 'industry_id';
    }

}