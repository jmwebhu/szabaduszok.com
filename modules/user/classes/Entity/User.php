<?php

class Entity_User extends Entity
{
    /**
     * @var Text_User
     */
    protected $_text;

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
     * @var datetime
     */
    protected $_created_at;

    /**
     * @var datetime
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

    public function submit(array $data)
    {
        $post['type']               = $this->_model->getType();
        $landing                    = Model_Landing::byName(Arr::get($post, 'landing_page_name'));
        $post['landing_page_id']    = $landing->landing_page_id;
        $id                         = Arr::get($post, 'user_id');
        $userModel                  = new Model_User();

        $userWithEmail = $userModel->byEmail(Arr::get($post, 'email'));

        if ($id) {
            $this->_model->where('user_id', '=', $id)->find();

            $userWithEmail  = $userWithEmail->byNotId($id);

            if (!Arr::get($post, 'password')) {
                unset($post['password']);
                unset($post['password_again']);
            }
        }

        $userWithEmail = $userWithEmail->limit(1)->find();

        if ($userWithEmail->loaded()) {
            throw new Exception_UserRegistration('Ezzel az e-mail címmel már regisztráltak. Kérjük válassz másikat, vagy jelentkezz be.');
        }

        $this->_text->fixPostalCode($post);

        // Szabaduszo
        $this->_text->fixUrl($post, 'webpage');

        // Megbizo
        $this->_text->alterCheckboxValue($post);

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

        $this->_file->uploadFiles();
        $this->_business->saveSearchText();

        $this->_model->last_login = time();
        $this->_model->save();

        $this->_model->cacheToCollection();

        $this->_model->updateSession();

        $signupModel = new Model_Signup();
        $signupModel->deleteIfExists($this->email);

        $this->mapModelToThis();
    }
}