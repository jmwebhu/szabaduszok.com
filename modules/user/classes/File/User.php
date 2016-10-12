<?php

abstract class File_User
{
    const PATH_PROFILE_PICTURE          = DOCROOT . 'uploads/picture';
    const FILE_PREFFIX                  = 'szabaduszok-';
    const PROFILE_PICTURE_SUFFIX        = '-pic';
    const PROFILE_PICTURE_LIST_SUFFIX   = '-pic-list';

    /**
     * @var Model_User
     */
    protected $_user;

    /**
     * @var array
     */
    protected $_profilePictureFile;

    /**
     * @var string
     */
    protected $_profilePictureFilename;

    /**
     * @var string
     */
    protected $_profilePictureListFilename;

    /**
     * @var string
     */
    protected $_tempProfilePictureFilename;

    /**
     * @var string
     */
    protected $_tempProfilePictureListFilename;

    /**
     * @param Model_User $user
     */
    public function __construct(Model_User $user)
    {
        $this->_user = $user;
    }

    public function uploadFiles()
    {
        if (isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture']['name'])) {
            $this->_profilePictureFile = $_FILES['profile_picture'];
            $filenames = $this->uploadProfilePicture();

            $this->_user->profile_picture_path = Arr::get($filenames, 'profile');
            $this->_user->list_picture_path	= Arr::get($filenames, 'list');
            $this->_user->save();
        }
    }

    /**
     * @return array|bool
     * @throws Exception_UserRegistration
     */
    protected function uploadProfilePicture()
    {
        if (!$this->validateImageFile()) {
            return false;
        }

        $extension 	    = Upload::getExt($this->_profilePictureFile);
        $filename 	    = strtolower($this->_user->slug . '.' . $extension);
        $fullFilename   = Upload::save($this->_profilePictureFile, $filename, self::PATH_PROFILE_PICTURE);

        if (!$fullFilename) {
            throw new Exception_UserRegistration('Hiba történt a profilkép feltöltése során. Kérjük próbáld meg újra.');
        }

        $this->setProfilePictureFilenameWith($extension);
        $this->setProfilePictureListFilenameWith($extension);

        $this->saveImages();

        unlink($fullFilename);

        return [
            'profile' 	=> $this->_tempProfilePictureFilename,
            'list'		=> $this->_tempProfilePictureListFilename
        ];
    }

    /**
     * @return bool
     */
    protected function validateImageFile()
    {
        return !(!Upload::valid($this->_profilePictureFile) || !Upload::not_empty($this->_profilePictureFile)
            || !Upload::type($this->_profilePictureFile, ['jpg', 'jpeg', 'png', 'gif', 'bmp']));
    }

    /**
     * @param string $extension
     */
    protected function setProfilePictureFilenameWith($extension)
    {
        $this->_tempProfilePictureFilename  = $this->getBaseFilenameWith(self::PROFILE_PICTURE_SUFFIX) . '.' . $extension;
        $this->_profilePictureFilename      = $this->getFullPathBy($this->_tempProfilePictureFilename);
    }

    /**
     * @param string $extension
     */
    protected function setProfilePictureListFilenameWith($extension)
    {
        $this->_tempProfilePictureListFilename  = $this->getBaseFilenameWith(self::PROFILE_PICTURE_LIST_SUFFIX) . '.' . $extension;
        $this->_profilePictureListFilename      = $this->getFullPathBy($this->_tempProfilePictureListFilename);
    }

    /**
     * @param string $suffix
     * @return string
     */
    protected function getBaseFilenameWith($suffix)
    {
        return self::FILE_PREFFIX . $this->_user->slug . $suffix;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function getFullPathBy($filename)
    {
        return self::PATH_PROFILE_PICTURE . DIRECTORY_SEPARATOR . $filename;
    }

    protected function saveImages()
    {
        Image::factory($this->_tempProfilePictureFilename)
            ->resize(200, 200, Image::AUTO)
            ->save($this->_profilePictureFilename);

        Image::factory($this->_tempProfilePictureListFilename)
            ->resize(200, 200, Image::HEIGHT)
            ->save($this->_profilePictureListFilename);
    }
}