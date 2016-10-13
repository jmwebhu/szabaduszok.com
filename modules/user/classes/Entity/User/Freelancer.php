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
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        parent::__construct();
        $this->_model       = new Model_User_Freelancer($id);
        $this->_file        = new File_User_Freelancer($this->_model);
        $this->_business    = new Business_User_Freelancer($this->_model);

        if ($id) {
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
}