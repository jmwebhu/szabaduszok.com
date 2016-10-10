<?php

/**
 * Observer patternhez tartozo Subject. Azert nem absztrakt osztaly, mert a subject -ek minden esetben ORM alosztalyok,
 * amik alapbol tartalmazzak a kapcsolataikat, es mindig ok lesznek az Observerek.
 */

interface Subject {
    public function notifyObservers($event);
}