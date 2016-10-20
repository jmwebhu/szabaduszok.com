<?php

class Pager
{
    /**
     * @var array
     */
    protected $_items = [];

    /**
     * @var int
     */
    protected $_currentPage;

    /**
     * @var int
     */
    protected $_limit;

    /**
     * @var int
     */
    protected $_pagesCount = null;

    /**
     * @var string
     */
    protected $_baseUrl;

    /**
     * @return int
     */
    public function getPagesCount()
    {
        return ($this->_pagesCount) ? $this->_pagesCount : $this->initPagesCount();
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->_currentPage;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_baseUrl;
    }

    /**
     * @param array $items
     * @param int $currentPage
     * @param int $limit
     * @param string $baseUrl
     */
    public function __construct(array $items, $currentPage, $limit, $baseUrl)
    {
        $this->_items       = $items;
        $this->_currentPage = $currentPage;
        $this->_limit       = $limit;
        $this->_baseUrl     = $baseUrl;

        $this->initPagesCount();
    }

    /**
     * @return array
     */
    public function getPagesArray()
    {
        $pages = [];
        for ($i = 1; $i <= $this->_pagesCount; $i++) {
            $pages[] = $i;
        }

        return $pages;
    }

    /**
     * @return int
     */
    public function getPreviousPage()
    {
        if ($this->_currentPage == 1) {
            return 1;
        }

        return $this->_currentPage - 1;
    }

    /**
     * @return int
     */
    public function getNextPage()
    {
        if ($this->_currentPage == $this->_pagesCount) {
            return $this->_pagesCount;
        }

        return $this->_currentPage + 1;
    }

    /**
     * @return int
     */
    protected function initPagesCount()
    {
        $pagesCountFloat            = count($this->_items) / $this->_limit;     // Pl.: 33 / 10 = 3.3
        $pagesCountInt 			    = floor($pagesCountFloat);                  // 3.3 => 3
        $pagesCountDecimalRemainder	= $pagesCountFloat - $pagesCountInt;        // 3.3 - 3 = 0.3

        // Ha van tizedes maradek, akkor egyel tobb lap kell
        if ($pagesCountDecimalRemainder != 0) {
            $pagesCountInt++;
        }

        $this->_pagesCount = ($pagesCountInt);

        return $this->_pagesCount;
    }
}