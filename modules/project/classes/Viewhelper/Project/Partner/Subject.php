<?php

abstract class Viewhelper_Project_Partner_Subject
{
    /**
     * @var ORM
     */
    protected $_orm;

    /**
     * @param ORM $_orm
     */
    public function __construct(ORM $_orm)
    {
        $this->_orm = $_orm;
    }

    /**
     * @return array
     */
    public function getPartnersSeparatedByType()
    {
        $partnersEntity = [];
        foreach ($this->getPartners() as $i => $partner) {
            /**
             * @var Model_Project_Partner $partner
             */

            $typePlural = $partner->getTypePlural();

            if (!isset($partnersEntity[$typePlural])) {
                $partnersEntity[$typePlural] = [];
            }

            $relationClass                                                      = $this->getRelationEntityClass();
            $partnersEntity[$typePlural][$i]                                    = [];
            $partnersEntity[$typePlural][$i][$this->getRelationObjectName()]    = new $relationClass($partner->{$this->getRelationObjectName()});
            $partnersEntity[$typePlural][$i]['project_partner']                 = $partner;
        }

        return $partnersEntity;
    }

    /**
     * @return array
     */
    abstract public function getPartners();

    /**
     * @return string
     */
    abstract protected function getRelationEntityClass();

    /**
     * @return string
     */
    abstract protected function getRelationObjectName();
}