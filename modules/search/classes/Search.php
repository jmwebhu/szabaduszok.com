<?php

/**
 * Interface Search
 *
 * Keresesre szolgalo Strategy interface, az impletmentaciok tartalmazzak az osszetett es az egyszeru keresest
 *
 * @author Joo Martin
 * @version 1.0
 * @since 2.2
 */

interface Search
{
    /**
     * return array
     */
    public function search();

    /**
     * @return Array_Builder
     */
    public function getInitModels();

    /**
     * @return ORM
     */
    public function createSearchModel();
}