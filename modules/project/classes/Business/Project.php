<?php

/**
 * Class Business_Project
 *
 * Felelosseg: Projekthez tartozo altalanos uzleti logika
 */

class Business_Project extends Business
{
    /**
     * @var Business_Project_Searchtext
     */
    protected $_searchText;

    /**
     * @param ORM $model
     */
    public function __construct(ORM $model)
    {
        parent::__construct($model);
        $this->_searchText = new Business_Project_Searchtext($this->_model);
    }

    /**
     * @param Model_Project_Relation $relation
     * @return string
     */
    public static function getRelationIdField(Model_Project_Relation $relation)
    {
        $model = $relation->getEndRelationModel();
        return $model->primary_key();
    }

    /**
     * @param string $field
     * @param int $maxChars
     * @return mixed|string
     */
    public function getFieldCutOffAt($field, $maxChars = 75)
    {
        if (strlen($this->_model->{$field}) > $maxChars) {
            return mb_substr($this->_model->{$field}, 0, $maxChars) . '...';
        }

        return $this->_model->{$field};
    }

    /**
     * @return string
     */
    public function getSearchTextFromFields()
    {
        return $this->_searchText->getSearchTextFromFields();
    }
}