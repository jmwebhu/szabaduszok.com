<?php

abstract class Entity_User extends Entity implements Notifiable, Conversation_Participant
{
    const TYPE_FREELANCER       = 1;
    const TYPE_EMPLOYER         = 2;
    const TYPE_SLUG_FREELANCER  = 'szabaduszo';
    const TYPE_SLUG_EMPLOYER    = 'megbizo';

    /**
     * @var File_User
     */
    protected $_file;

    /**
     * @var Search
     */
    protected $_search;

    /**
     * @var array
     */
    protected $_notifiers = [];

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
    protected $_landing_page_id;

    /**
     * @var string
     */
    protected $_salt;

    /**
     * Entity_User constructor.
     */
    public function __construct()
    {
        $this->_stdObject   = new stdClass();
        $this->_notifiers[] = new Notifier_Email();
        $this->_notifiers[] = new Notifier_Socket();
    }

    /**
     * @return string
     */
    abstract public function getTypeSlug();

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
    public function getLandingPageId()
    {
        return $this->_landing_page_id;
    }

    /**
     * @return File_User
     */
    public function getFile()
    {
        return $this->_file;
    }

    /**
     * @return Search
     */
    public function getSearch()
    {
        return $this->_search;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->_salt;
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
     * @param string $profile_picture_path
     */
    public function setProfilePicturePath($profile_picture_path)
    {
        $this->_profile_picture_path = $profile_picture_path;
    }

    /**
     * @param Search $search
     */
    public function setSearch(Search $search)
    {
        $this->_search = $search;
    }

    /**
     * @param array $post
     * @return Entity_User
     * @throws Exception_UserRegistration
     */
    public function submitUser(array $post, $mailinglist = null)
    {
        $result = ['error' => false];

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

        if ($mailinglist == null) {
            $mailinglist    = Gateway_Mailinglist_Factory::createMailinglist($this);
        }

        $mailinglist->add((bool)$id);

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
     * @param mixed $value
     * @return Entity_User
     */
    public static function createUser($type, $value = null)
    {
        switch ($type) {
            case self::TYPE_FREELANCER:
                return new Entity_User_Freelancer($value);

            case self::TYPE_EMPLOYER:
                return new Entity_User_Employer($value);
        }

        Assert::neverShouldReachHere();
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

    /**
     * @return Search
     */
    public function search()
    {
        return $this->_search->search();
    }

    /**
     * @param Notification $notification
     */
    public function setNotification(Notification $notification)
    {
        foreach ($this->_notifiers as $notifier) {
            /**
             * @var Notifier $notifier
             */
            $notifier->setNotification($notification);
        }
    }

    /**
     * @return bool
     */
    public function sendNotification()
    {
        $result = true;
        try {
            foreach ($this->_notifiers as $notifier) {
                /**
                 * @var Notifier $notifier
                 */
                if (!$notifier->sendNotificationTo($this)) {
                    throw new Exception('Notificaion not sent. Notification: '
                        . $notifier->getNotification()->getId() . ' Notifiable: ' . $this->getId() . ' Notifier: ' . $notifier->getTemplateFormat());
                }
            }
        } catch (Exception $ex) {
            $result = false;
            Log::instance()->addException($ex);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getNotifiers()
    {
        return $this->_notifiers;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_user_id;
    }

    /**
     * @return string
     */
    public function getJson()
    {
        return $this->_model->getJson();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->object();
    }
    
}