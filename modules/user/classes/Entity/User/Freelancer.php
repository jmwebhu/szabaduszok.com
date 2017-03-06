<?php

class Entity_User_Freelancer extends Entity_User
{
    /**
     * @var int
     */
    protected $_min_net_hourly_wage;

    /**
     * @var string
     */
    protected $_cv_path;

    /**
     * @var int
     */
    protected $_skill_relation;

    /**
     * @var int
     */
    protected $_need_project_notification;

    /**
     * @var string
     */
    protected $_webpage;

    /**
     * @var float
     */
    protected $_professional_experience;

    /**
     * @var bool
     */
    protected $_is_able_to_bill;

    /**
     * @return string
     */
    public function getTypeSlug()
    {
        return Entity_User::TYPE_SLUG_FREELANCER;
    }

    /**
     * @return int
     */
    public function getMinNetHourlyWage()
    {
        return $this->_min_net_hourly_wage;
    }

    /**
     * @return string
     */
    public function getCvPath()
    {
        return $this->_cv_path;
    }

    /**
     * @return int
     */
    public function getSkillRelation()
    {
        return $this->_skill_relation;
    }

    /**
     * @return int
     */
    public function getNeedProjectNotification()
    {
        return $this->_need_project_notification;
    }

    /**
     * @return string
     */
    public function getWebpage()
    {
        return $this->_webpage;
    }

    /**
     * @return float
     */
    public function getProfessionalExperience()
    {
        return $this->_professional_experience;
    }

    /**
     * @return bool
     */
    public function getIsAbleToBill()
    {
        return $this->_is_able_to_bill;
    }

    /**
     * @param string $cvPath
     */
    public function setCvPath($cvPath)
    {
        $this->_cv_path = $cvPath;
    }

    /**
     * @param null|int|ORM $value
     */
    public function __construct($value = null)
    {
        parent::__construct();

        if (is_object($value)) {
            $this->_model       = $value;
        } else {
            $this->_model       = new Model_User_Freelancer($value);
        }

        $this->_file        = new File_User_Freelancer($this->_model);
        $this->_business    = new Business_User_Freelancer($this->_model);

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
        $data = Text_User::alterCheckboxAbleToBillValue($data);
        return Text_User::fixUrl($data, 'webpage');
    }

    /**
     * @return string
     */
    public function getName()
    {
        $name = parent::getName();
        return (empty(trim($name))) ? 'Szabadúszó' : $name;
    }

    /**
     * @return bool
     */
    public function hasProjectNotification()
    {
        return $this->_model->hasProjectNotification();
    }

    /**
     * @param Model_User_Profile $userProfile
     * @return array
     */
    public function getProfileUrls(Model_User_Profile $userProfile)
    {
        return $this->_model->getProfileUrls($userProfile);
    }
}