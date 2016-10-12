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