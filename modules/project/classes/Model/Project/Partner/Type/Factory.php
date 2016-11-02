<?php

class Model_Project_Partner_Type_Factory
{
    /**
     * @param $typeId
     * @return Model_Project_Partner_Type
     */
    public static function createType($typeId = null)
    {
        $type = null;
        switch ($typeId) {
            case Model_Project_Partner::TYPE_CANDIDATE:
                $type = new Model_Project_Partner_Type_Candidate();
                break;

            case Model_Project_Partner::TYPE_PARTICIPANT:
                $type = new Model_Project_Partner_Type_Participant();
                break;

            case null: default:
                $type = new Model_Project_Partner_Type_Candidate();
                break;
        }

        Assert::notNull($type);
        return $type;
    }
}