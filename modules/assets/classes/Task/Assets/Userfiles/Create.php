<?php
/**
 * User: Tibor
 * Date: 2013.07.15.
 * Time: 21:02
 * Project: d2cadmin3.3
 * Company: d2c
 */
class Task_Assets_Userfiles_Create extends Minion_Task
{
    protected $_options = array(
    );

    public function build_validation(Validation $validation) {
        return parent::build_validation($validation);
    }

    protected function _execute(array $params)
    {
        $root = realpath(APPPATH.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $array = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');

        $userfiles = $root."userfiles";
        $others = $userfiles.DIRECTORY_SEPARATOR."_others";

        if (!file_exists($userfiles)) {
            mkdir($userfiles,0777,true);
            echo "username\n";
        }
        if (!file_exists($others)) {
            mkdir($others,0777,true);
            echo "|_ _others\n";
        }

        foreach($array as $item)
        {
            $first = $userfiles.DIRECTORY_SEPARATOR.$item;
            if (!file_exists($first)) {
                mkdir($first,0777,true);
                echo "|_ ".$item."\n";
            }
            foreach($array as $item2)
            {
                $second = $first.DIRECTORY_SEPARATOR.$item2;
                if (!file_exists($second)) {
                    mkdir($second,0777,true);
                    echo "|__ ".$item2."\n";
                }
            }
        }
    }

}