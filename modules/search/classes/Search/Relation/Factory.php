<?php

interface Search_Relation_Factory
{
    /**
     * @param Search_Complex $complex
     * @return Search_Relation
     */
    public static function makeSearch(Search_Complex $complex);
}