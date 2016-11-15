<?php

/**
 * Class Entity_Project
 *
 * Felelosseg: Projekt model mappelese, Facade biztositasa Projekt kulonbozo szolgaltatsaihoz
 */

class Entity_Project extends Entity implements Notification_Subject
{
    /**
     * @var Search
     */
    private $_search;

    /**
     * @var int
     */
    protected $_project_id;
    /**
     * @var int
     */
    protected $_user_id;
    /**
     * @var String
     */
    protected $_name;
    /**
     * @var String
     */
    protected $_short_description;
    /**
     * @var String
     */
    protected $_long_description;
    /**
     * @var String
     */
    protected $_email;
    /**
     * @var String
     */
    protected $_phonenumber;
    /**
     * @var int
     */
    protected $_is_active;
    /**
     * @var int
     */
    protected $_is_paid;
    /**
     * @var String
     */
    protected $_search_text;
    /**
     * @var String
     */
    protected $_created_at;
    /**
     * @var String
     */
    protected $_updated_at;
    /**
     * @var String
     */
    protected $_expiration_date;
    /**
     * @var int
     */
    protected $_salary_type;
    /**
     * @var float
     */
    protected $_salary_low;
    /**
     * @var float
     */
    protected $_salary_high;
    /**
     * @var String
     */
    protected $_slug;

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->_project_id;
    }

    /**
     * @param int $project_id
     */
    public function setProjectId($project_id)
    {
        $this->_project_id = $project_id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->_user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->_user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return String
     */
    public function getShortDescription()
    {
        return $this->_short_description;
    }

    /**
     * @param String $short_description
     */
    public function setShortDescription($short_description)
    {
        $this->_short_description = $short_description;
    }

    /**
     * @return String
     */
    public function getLongDescription()
    {
        return $this->_long_description;
    }

    /**
     * @param String $long_description
     */
    public function setLongDescription($long_description)
    {
        $this->_long_description = $long_description;
    }

    /**
     * @return String
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param String $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return String
     */
    public function getPhonenumber()
    {
        return $this->_phonenumber;
    }

    /**
     * @param String $phonenumber
     */
    public function setPhonenumber($phonenumber)
    {
        $this->_phonenumber = $phonenumber;
    }

    /**
     * @return int
     */
    public function getIsActive()
    {
        return $this->_is_active;
    }

    /**
     * @param int $is_active
     */
    public function setIsActive($is_active)
    {
        $this->_is_active = $is_active;
    }

    /**
     * @return int
     */
    public function getIsPaid()
    {
        return $this->_is_paid;
    }

    /**
     * @param int $is_paid
     */
    public function setIsPaid($is_paid)
    {
        $this->_is_paid = $is_paid;
    }

    /**
     * @return String
     */
    public function getSearchText()
    {
        return $this->_search_text;
    }

    /**
     * @param String $search_text
     */
    public function setSearchText($search_text)
    {
        $this->_search_text = $search_text;
    }

    /**
     * @return String
     */
    public function getCreatedAt()
    {
        return $this->_created_at;
    }

    /**
     * @param String $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->_created_at = $created_at;
    }

    /**
     * @return String
     */
    public function getUpdatedAt()
    {
        return $this->_updated_at;
    }

    /**
     * @param String $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->_updated_at = $updated_at;
    }

    /**
     * @return String
     */
    public function getExpirationDate()
    {
        return $this->_expiration_date;
    }

    /**
     * @param String $expiration_date
     */
    public function setExpirationDate($expiration_date)
    {
        $this->_expiration_date = $expiration_date;
    }

    /**
     * @return int
     */
    public function getSalaryType()
    {
        return $this->_salary_type;
    }

    /**
     * @param int $salary_type
     */
    public function setSalaryType($salary_type)
    {
        $this->_salary_type = $salary_type;
    }

    /**
     * @return float
     */
    public function getSalaryLow()
    {
        return $this->_salary_low;
    }

    /**
     * @param float $salary_low
     */
    public function setSalaryLow($salary_low)
    {
        $this->_salary_low = $salary_low;
    }

    /**
     * @return float
     */
    public function getSalaryHigh()
    {
        return $this->_salary_high;
    }

    /**
     * @param float $salary_high
     */
    public function setSalaryHigh($salary_high)
    {
        $this->_salary_high = $salary_high;
    }

    /**
     * @return String
     */
    public function getSlug()
    {
        return $this->_slug;
    }

    /**
     * @param String $slug
     */
    public function setSlug($slug)
    {
        $this->_slug = $slug;
    }

    /**
     * @return Search
     */
    public function getSearch()
    {
        return $this->_search;
    }

    /**
     * @param Search $search
     */
    public function setSearch(Search $search)
    {
        $this->_search = $search;
    }

    /**
     * @return array
     */
    public function search()
    {
        return $this->_search->search();
    }

    /**
     * @param int $maxChar
     * @return string
     */
    public function getShortDescriptionCutOffAt($maxChar = 100)
    {
        return $this->_business->getFieldCutOffAt('short_description', $maxChar);
    }

    /**
     * @param int $maxChar
     * @return string
     */
    public function getNameCutOffAt($maxChar = 70)
    {
        return $this->_business->getFieldCutOffAt('name', $maxChar);
    }

    public function inactivate()
    {
        $this->_model->inactivate();
        $this->mapModelToThis();
    }

    /**
     * @return string
     */
    public function getSearchTextFromFields()
    {
        return $this->_business->getSearchTextFromFields();
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getOrderedAndLimited($limit, $offset)
    {
        return $this->_model->getOrderedAndLimited($limit, $offset);
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->_model->getCount();
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return ($this->_model->loaded() && $this->_model->is_active);
    }

    /**
     * @param array $data
     * @return Entity_Project|bool
     */
    public function submit(array $data)
    {
        $submit = parent::submit($data);

        if (!$submit) {
            throw new Exception();
        }

        $this->_search_text = $this->_business->getSearchTextFromFields();
        $save = $this->save();

        if (!$save) {
            throw new Exception();
        }

        $this->_model->cacheToCollection();

        return $this;
    }

    /**
     * @return bool
     */
    public function isSalariesEqual()
    {
        return $this->getSalaryLow() == $this->getSalaryHigh() || !$this->getSalaryHigh();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_model->object();
    }
}
