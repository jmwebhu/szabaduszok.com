<?php

class Viewhelper_Project_Partner
{
    /**
     * @var Viewhelper_Project_Partner_Subject
     */
    protected $_subject;

    /**
     * @param ORM $subjectOrm
     */
    public function __construct(ORM $subjectOrm)
    {
        $this->setSubject($subjectOrm);
    }

    /**
     * @param ORM $subjectOrm
     */
    public function setSubject(ORM $subjectOrm)
    {
        $this->_subject = Viewhelper_Project_Partner_Subject_Factory::createSubject($subjectOrm);
    }

    /**
     * @return array
     */
    public function getPartnersSeparatedByType()
    {
        return $this->_subject->getPartnersSeparatedByType();
    }

    /**
     * @return array
     */
    public function getPartners()
    {
        return $this->_subject->getPartners();
    }
}