<?php

abstract class Entity_User extends Entity
{
    /**
     * @var int
     */
    const TYPE_FREELANCER   = 1;

    /**
     * @var int
     */
    const TYPE_EMPLOYER     = 2;

    /**
     * @var File_User
     */
    protected $_file;

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
    protected $_slug;

    /**
     * @var int
     */
    protected $_type;

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
     * Entity_User constructor.
     */
    public function __construct()
    {
        $this->_stdObject   = new stdClass();
    }

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
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->_lastname = $lastname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->_firstname = $firstname;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->_slug = $slug;
    }

    /**
     * @param array $post
     * @return Entity_User
     * @throws Exception_UserRegistration
     */
    public function submit(array $post)
    {
        $data                       = $post;
        $id                         = Arr::get($data, 'user_id');
        $userModel                  = new Model_User();
        $userWithEmail              = $userModel->getByEmail(Arr::get($data, 'email'), $id);

        if ($userWithEmail->loaded()) {
            throw new Exception_UserRegistration('Ezzel az e-mail címmel már regisztráltak. Kérjük válassz másikat, vagy jelentkezz be.');
        }

        $data['type']               = $this->_model->getType();
        $landing                    = Model_Landing::byName(Arr::get($data, 'landing_page_name'));
        $data['landing_page_id']    = $landing->landing_page_id;

        $data = $this->unsetPasswordFrom($data);
        $data = $this->fixPost($data);

        $this->_model->updateSession();

        $this->_model->submit($data);

        $this->_file->uploadFiles();

        $this->_model->search_text = $this->_business->getSearchTextFromFields();
        $this->_model->save();

        $this->_model->cacheToCollection();

        $signupModel = new Model_Signup();
        $signupModel->deleteIfExists($this->_model->email);

        $this->mapModelToThis();

        return $this;
    }

    /**
     * @param array $post
     * @return array
     */
    protected function fixPost(array $post)
    {
        return Text_User::fixPostalCode($post);
    }

    /**
     * @param array $post
     * @return array
     */
    protected function unsetPasswordFrom(array $post)
    {
        $data = $post;
        if (Arr::get($data, 'user_id') && !Arr::get($data, 'password')) {
            unset($data['password']);
            unset($data['password_again']);
        }

        return $data;
    }

    /**
     * @param int $type
     * @return Entity_User
     */
    public static function createUser($type, $id = null)
    {
        switch ($type) {
            case self::TYPE_FREELANCER:
                return new Entity_User_Freelancer($id);

            case self::TYPE_EMPLOYER:
                return new Entity_User_Employer($id);
        }
    }

    /**
     * @return string
     */
    public function getLastLoginFormatted()
    {
        return $this->_business->getLastLoginFormatted();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return SB::create($this->_lastname)->append(' ')->append($this->_firstname)->get();
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->_model->getCount();
    }

    /**
     * @param string $relationName
     * @return string
     */
    public function getRelationString($relationName)
    {
        return $this->_model->getRelationString($relationName);
    }

    /**
     * @param array $data
     * @return array
     */
    public function saveProjectNotification(array $data)
    {
        return $this->_model->saveProjectNotification($data);
    }
}