<?php

class Entity_User_Employer extends Entity_User
{
    /**
     * @var string
     */
    protected $_phonenumber;

    /**
     * @var int
     */
    protected $_is_company;

    /**
     * @var string
     */
    protected $_company_name;

    /**
     * @return string
     */
    public function getTypeSlug()
    {
        return Entity_User::TYPE_SLUG_EMPLOYER;
    }

    /**
     * @return string
     */
    public function getPhonenumber()
    {
        return $this->_phonenumber;
    }

    /**
     * @return int
     */
    public function getIsCompany()
    {
        return $this->_is_company;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->_company_name;
    }

    /**
     * @return array
     */
    public function getProjects()
    {
        return $this->_model->getProjects();
    }

    /**
     * @param int|ORM|null $value
     */
    public function __construct($value = null)
    {
        parent::__construct();

        if (is_object($value)) {
            $this->_model       = $value;
        } else {
            $this->_model       = new Model_User_Employer($value);
        }

        $this->_file        = new File_User_Employer($this->_model);
        $this->_business    = new Business_User_Employer($this->_model);

        if ($value) {
            $this->mapModelToThis();
        }
    }

    /**
     * @param array $post
     * @return array
     */
    protected function fixPost(array $post)
    {
        $data = parent::fixPost($post);
        return Text_User::alterCheckboxCompanyValue($data);
    }
}