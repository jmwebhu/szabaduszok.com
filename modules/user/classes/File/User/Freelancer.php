<?php

class File_User_Freelancer extends File_User
{
    const PATH_CV   = DOCROOT . 'uploads/cv';
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
        if (!$this->validateCvFile()) {
            throw new Exception_UserRegistration('Hibás önéletrajz formátum. Kérjük próbáld meg újra.');
        }

        $extension      = Upload::getExt($this->_cvFile);
        $filename       = strtolower(self::FILE_PREFFIX .  $this->_user->slug . self::CV_SUFFIX . '.' . $extension);
        $fullFilename   = Upload::save($this->_cvFile, $filename, self::PATH_CV);

        if (!$fullFilename) {
            throw new Exception_UserRegistration('Hiba történt az önéletrajz feltöltése során. Kérjük próbáld meg újra.');
        }

        return $filename;
    }

    /**
     * @return bool
     */
    protected function validateCvFile()
    {
        return !(!Upload::valid($this->_cvFile) || !Upload::not_empty($this->_cvFile)
            || !Upload::type($this->_cvFile, ['pdf', 'doc', 'docx', 'pages', 'odt', 'wps', 'wpd', 'rtf', 'docm']));
    }
}