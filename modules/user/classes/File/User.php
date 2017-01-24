<?php

abstract class File_User
{
    const PATH_PROFILE_PICTURE          = 'uploads/picture';
    const FILE_PREFFIX                  = 'szabaduszok-';
    const PROFILE_PICTURE_SUFFIX        = '-pic';
    const PROFILE_PICTURE_LIST_SUFFIX   = '-pic-list';

    /**
     * @var Model_User
     */
    public $_user;

    /**
     * @var array
     */
    protected $_profilePictureFile;

    /**
     * @var string
     */
    protected $_relativeProfilePictureFilename;

    /**
     * @var string
     */
    protected $_absoluteProfilePictureFilename;

    /**
     * @var string
     */
    protected $_absoluteProfilePictureListFilename;

    /**
     * @var string
     */
    protected $_relativeProfilePictureListFilename;

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
            $this->_profilePictureFile  = $_FILES['profile_picture'];
            $filenames                  = $this->uploadProfilePicture();

            $this->_user->profile_picture_path  = Arr::get($filenames, 'profile');
            $this->_user->list_picture_path     = Arr::get($filenames, 'list');
            $this->_user->save();
        }
    }

    /**
     * @return array|bool
     * @throws ORM_Validation_Exception
     */
    protected function uploadProfilePicture()
    {
        $this->throwException($this->validateImageFile(), 'profile_picture_path');
        $extension = Upload::getExt($this->_profilePictureFile);

        $this->setProfilePictureFilenameWith($extension);
        $this->setProfilePictureListFilenameWith($extension);

        $sourceFilename = Upload::save($this->_profilePictureFile, $this->_relativeProfilePictureFilename, DOCROOT . self::PATH_PROFILE_PICTURE);
        $this->throwException($sourceFilename, 'profile_picture_path');

        if (!File::validateByMimes($sourceFilename, ['image/jpeg', 'image/pjpeg', 'image/png'])) {
            $this->throwException(false, 'profile_picture_path');
            unlink($sourceFilename);
        }

        $this->saveImagesFrom($sourceFilename);

        return [
            'profile' 	=> $this->_relativeProfilePictureFilename,
            'list'		=> $this->_relativeProfilePictureListFilename
        ];
    }

    /**
     * @param mixed $expression
     * @param string $field
     * @throws ORM_Validation_Exception
     */
    protected function throwException($expression, $field)
    {
        if (!$expression) {
            $array = [
                $field => ''
            ];

            $validation = Validation::factory($array);
            $validation->rule($field, 'not_empty');

            if (!$validation->check()) {
                throw new ORM_Validation_Exception('user', $validation);
            }
        }
    }

    /**
     * @return bool
     */
    protected function validateImageFile()
    {
        return !(!Upload::valid($this->_profilePictureFile) || !Upload::not_empty($this->_profilePictureFile)
            || !Upload::type($this->_profilePictureFile, ['jpg', 'jpeg', 'png']));
    }

    /**
     * @param string $extension
     */
    protected function setProfilePictureFilenameWith($extension)
    {
        $this->_relativeProfilePictureFilename  = $this->getBaseFilenameWith(self::PROFILE_PICTURE_SUFFIX) . '.' . $extension;
        $this->_absoluteProfilePictureFilename  = $this->getFullPathBy($this->_relativeProfilePictureFilename);
    }

    /**
     * @param string $extension
     */
    protected function setProfilePictureListFilenameWith($extension)
    {
        $this->_relativeProfilePictureListFilename  = $this->getBaseFilenameWith(self::PROFILE_PICTURE_LIST_SUFFIX) . '.' . $extension;
        $this->_absoluteProfilePictureListFilename  = $this->getFullPathBy($this->_relativeProfilePictureListFilename);
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
        return DOCROOT . self::PATH_PROFILE_PICTURE . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * @param $sourceFilename
     */
    protected function saveImagesFrom($sourceFilename)
    {
        Image::factory($sourceFilename)
            ->resize(200, 200, Image::AUTO)
            ->save($this->_absoluteProfilePictureFilename);

        Image::factory($sourceFilename)
            ->resize(200, 200, Image::HEIGHT)
            ->save($this->_absoluteProfilePictureListFilename);
    }
}