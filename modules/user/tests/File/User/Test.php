<?php

class File_User_Test extends Unittest_TestCase
{
    /**
     * @covers File_User::getBaseFilenameWith()
     */
    public function testGetBaseFilenameWith()
    {
        $user = new Model_User();
        $user->slug = 'joo-martin';

        $file = new File_User_Employer($user);
        $baseFilenameProfile = $this->invokeMethod($file, 'getBaseFilenameWith', [File_User::PROFILE_PICTURE_SUFFIX]);
        $baseFilenameList = $this->invokeMethod($file, 'getBaseFilenameWith', [File_User::PROFILE_PICTURE_LIST_SUFFIX]);

        $this->assertEquals('szabaduszok-joo-martin-pic', $baseFilenameProfile);
        $this->assertEquals('szabaduszok-joo-martin-pic-list', $baseFilenameList);
    }

    /**
     * @covers File_User::getFullPathBy()
     */
    public function testGetFullPathBy()
    {
        $user = new Model_User();
        $user->slug = 'joo-martin';

        $file = new File_User_Employer($user);
        $profileFilename = 'szabaduszok-joo-martin-pic';
        $listFilanem = 'szabaduszok-joo-martin-pic-list';

        $profileFullname = $this->invokeMethod($file, 'getFullPathBy', [$profileFilename]);
        $listFullname = $this->invokeMethod($file, 'getFullPathBy', [$listFilanem]);

        $this->assertEquals(DOCROOT . 'uploads/picture/' . $profileFilename, $profileFullname);
        $this->assertEquals(DOCROOT . 'uploads/picture/' . $listFilanem, $listFullname);
    }
}