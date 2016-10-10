<?php

/**
 * INterface Search_Factory
 *
 * Kereses Strategy gyartas
 */

interface Search_Factory
{
    /**
     * @param array $data
     * @return Search
     */
    public static function makeSearch(array $data);
}