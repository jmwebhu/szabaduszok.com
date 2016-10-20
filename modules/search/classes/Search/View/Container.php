<?php

abstract class Search_View_Container
{
    /**
     * Aktualis tipus (egyszeru, reszletes)
     * @var string
     */
    protected $_currentType;

    /**
     * @var Search_View_Container_Relation
     */
    protected $_relationContainer;

    /**
     * @var string
     */
    protected $_searchTerm;

    /**
     * @param string $currentType
     */
    public function __construct($currentType)
    {
        $this->_currentType = $currentType;
    }

    /**
     * @return mixed
     */
    public function getCurrentType()
    {
        return $this->_currentType;
    }

    /**
     * @return Search_View_Container_Relation
     */
    public function getRelationContainer()
    {
        return $this->_relationContainer;
    }

    /**
     * @return string
     */
    public function getSearchTerm()
    {
        return $this->_searchTerm;
    }

    /**
     * @param Search_View_Container_Relation $relationContainer
     */
    public function setRelationContainer($relationContainer)
    {
        if ($this->_relationContainer == null) {
            $this->_relationContainer = $relationContainer;
        }
    }

    /**
     * @param string $searchTerm
     */
    public function setSearchTerm($searchTerm)
    {
        if ($this->_searchTerm == null) {
            $this->_searchTerm = $searchTerm;
        }
    }

    /**
     * @return string
     */
    abstract public function getSimpleSubtitle();

    /**
     * @return string
     */
    abstract public function getHeadingText();

    /**
     * @return mixed
     */
    abstract public function getComplexFormAction();

    /**
     * @return mixed
     */
    abstract public function getSimpleFormAction();

    /**
     * @return bool
     */
    public function needTabs()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getComplexButtonsHtml()
    {
        return "<button id=\"complex-submit\" class=\"mt bloc-button btn-block btn btn-lg btn-lime-green\" type=\"submit\">
                        <span class=\"ion ion-search icon-spacer icon-white\"></span>Mehet
                    </button>" . $this->getClearButtonHtml();
    }

    /**
     * @return string
     */
    public function getSimpleButtonsHtml()
    {
        return "<button id=\"simple-submit\" class=\"mt bloc-button btn-block btn btn-lg btn-lime-green\" type=\"submit\">
                        <span class=\"ion ion-search icon-spacer icon-white\"></span>Mehet
                    </button>" . $this->getClearButtonHtml();
    }

    /**
     * @return string
     */
    protected function getClearButtonHtml()
    {
        return "<a id=\"empty-form\" class=\"bloc-button btn-block btn btn-lg btn-gray btn-d\" href=\"" . $this->getComplexFormAction() . "\">
                        <span class=\"ion ion-android-cancel icon-spacer icon-white\"></span>Ürít
                    </a>";
    }
}