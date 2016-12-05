<?php

class File_User_Freelancer extends File_User
{
    const PATH_CV   = 'uploads/cv';
    const CV_SUFFIX = '-cv';

    /**
     * @var array
     */
    private $_cvFile;

    public function uploadFiles()
    {
        parent::uploadFiles();

        if (isset($_FILES['cv']) && !empty($_FILES['cv']['name'])) {
            $this->_cvFile = $_FILES['cv'];
            $filename = $this->uploadCv();

            $this->_user->cv_path = $filename;
            $this->_user->save();
        }
    }

    /**
     * @return bool|string
     * @throws Exception_UserRegistration
     */
    protected function uploadCv()
    {
        $this->throwException($this->validateCvFile(), 'cv_path');

        $extension      = Upload::getExt($this->_cvFile);
        $filename       = strtolower(self::FILE_PREFFIX .  $this->_user->slug . self::CV_SUFFIX . '.' . $extension);
        $fullFilename   = Upload::save($this->_cvFile, $filename, DOCROOT . self::PATH_CV);
        $this->throwException($fullFilename, 'cv_path');

        if (!File::validateByMimes($fullFilename, ['application/msword', 'application/pdf', 'application/x-iwork-pages-sffpages', 'application/vnd.apple.pages', 'application/vnd.oasis.opendocument.text', 'application/zip'])) {
            $this->throwException(false, 'cv_path');
            unlink($fullFilename);
        }

        return $filename;
    }

    /**
     * @return bool
     */
    protected function validateCvFile()
    {
        return !(!Upload::valid($this->_cvFile) || !Upload::not_empty($this->_cvFile)
            || !Upload::type($this->_cvFile, ['pdf', 'doc', 'docx', 'pages', 'odt']));
    }
}