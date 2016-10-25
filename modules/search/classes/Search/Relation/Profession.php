<?php

class Search_Relation_Profession extends Search_Relation
{
    /**
     * @return string
     */
    protected function getModelPk()
    {
        return 'profession_id';
    }

}