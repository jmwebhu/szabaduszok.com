<?php

class Entity_User extends Entity
{
    /**
     * @var int
     */
    protected $_user_id;

    /**
     * @var string
     */
    protected $_lastname;

    /**
     * @var string
     */
    protected $_firstname;

    /**
     * @var string
     */
    protected $_email;

    /**
     * @var string
     */
    protected $_password;

    /**
     * @var int
     */
    protected $_logins;

    /**
     * @var int
     */
    protected $_last_login;

    /**
     * @var int
     */
    protected $_address_postal_code;

    /**
     * @var string
     */
    protected $_address_city;

    /**
     * @var string
     */
    protected $_address_street;

    /**
     * @var string
     */
    protected $_phonenumber;

    /**
     * @var string
     */
    protected $_slug;

    /**
     * @var int
     */
    protected $_type;

    /**
     * @var int
     */
    protected $_min_net_hourly_wage;

    /**
     * @var string
     */
    protected $_short_description;

    /**
     * @var string
     */
    protected $_profile_picture_path;

    /**
     * @var string
     */
    protected $_list_picture_path;

    /**
     * @var string
     */
    protected $_cv_path;

    /**
     * @var int
     */
    protected $_is_company;

    /**
     * @var string
     */
    protected $_company_name;

    /**
     * @var string
     */
    protected $_created_at;

    /**
     * @var string
     */
    protected $_updated_at;

    /**
     * @var int
     */
    protected $_rating_points_sum;

    /**
     * @var int
     */
    protected $_raters_count;

    /**
     * @var int
     */
    protected $_rating_points_avg;

    /**
     * @var int
     */
    protected $_skill_relation;

    /**
     * @var int
     */
    protected $_is_admin;

    /**
     * @var string
     */
    protected $_search_text;

    /**
     * @var int
     */
    protected $_old_user_id;

    /**
     * @var string
     */
    protected $_password_plain;

    /**
     * @var int
     */
    protected $_landing_page_id;

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
    public function getUserId()
    {
        return $this->_user_id;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->_lastname;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->_firstname;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @return int
     */
    public function getLogins()
    {
        return $this->_logins;
    }

    /**
     * @return int
     */
    public function getLastLogin()
    {
        return $this->_last_login;
    }

    /**
     * @return int
     */
    public function getAddressPostalCode()
    {
        return $this->_address_postal_code;
    }

    /**
     * @return string
     */
    public function getAddressCity()
    {
        return $this->_address_city;
    }

    /**
     * @return string
     */
    public function getAddressStreet()
    {
        return $this->_address_street;
    }

    /**
     * @return string
     */
    public function getPhonenumber()
    {
        return $this->_phonenumber;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->_slug;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->_type;
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
    public function getShortDescription()
    {
        return $this->_short_description;
    }

    /**
     * @return string
     */
    public function getProfilePicturePath()
    {
        return $this->_profile_picture_path;
    }

    /**
     * @return string
     */
    public function getListPicturePath()
    {
        return $this->_list_picture_path;
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
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->_created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->_updated_at;
    }

    /**
     * @return int
     */
    public function getRatingPointsSum()
    {
        return $this->_rating_points_sum;
    }

    /**
     * @return int
     */
    public function getRatersCount()
    {
        return $this->_raters_count;
    }

    /**
     * @return int
     */
    public function getRatingPointsAvg()
    {
        return $this->_rating_points_avg;
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
    public function getIsAdmin()
    {
        return $this->_is_admin;
    }

    /**
     * @return string
     */
    public function getSearchText()
    {
        return $this->_search_text;
    }

    /**
     * @return int
     */
    public function getOldUserId()
    {
        return $this->_old_user_id;
    }

    /**
     * @return string
     */
    public function getPasswordPlain()
    {
        return $this->_password_plain;
    }

    /**
     * @return int
     */
    public function getLandingPageId()
    {
        return $this->_landing_page_id;
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

    public function __construct($id)
    {
        parent::__construct($id);
    }

    public function submit(array $post)
    {
        $id                         = Arr::get($post, 'user_id');
        $userModel                  = new Model_User();
        $userWithEmail              = $userModel->getByEmail(Arr::get($post, 'email'), $id);

        if ($userWithEmail->loaded()) {
            throw new Exception_UserRegistration('Ezzel az e-mail címmel már regisztráltak. Kérjük válassz másikat, vagy jelentkezz be.');
        }

        $post['type']               = $this->_model->getType();
        $landing                    = Model_Landing::byName(Arr::get($post, 'landing_page_name'));
        $post['landing_page_id']    = $landing->landing_page_id;

        $this->unsetPasswordFrom($post);

        Text_User::fixPostalCode($post);

        // Szabaduszo
        Text_User::fixUrl($post, 'webpage');

        // Megbizo
        Text_User::alterCheckboxValue($post);

        if ($id) {
            $this->_model->update_user($post);
        }
        else {
            $this->_model->create_user($post);
        }

        $this->_model->saveSlug();

        $this->_model->addRelations($post);

        // Szabaduszo
        $this->_model->saveProjectNotificationByUser();

        File_User::uploadFiles();
        $this->_business->saveSearchText();

        $this->_model->last_login = time();
        $this->_model->save();

        $this->_model->cacheToCollection();

        $this->_model->updateSession();

        $signupModel = new Model_Signup();
        $signupModel->deleteIfExists($this->email);

        $this->mapModelToThis();
    }

    /**
     * @param array $post
     */
    protected function unsetPasswordFrom(array $post)
    {
        if (!Arr::get($post, 'password')) {
            unset($post['password']);
            unset($post['password_again']);
        }
    }
}