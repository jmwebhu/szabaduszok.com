<?php

/**
 * Class Business_Project_Searchtext
 *
 * Felelosseg: Projekt kereso szoveg eloallitasa
 */

class Business_Project_Searchtext
{
    /**
     * @var Model_Project
     */
    private $_model;

    /**
     * @param Model_Project $model
     */
    public function __construct(Model_Project $model)
    {
        $this->_model = $model;
    }

    /**
     * @return string
     */
    public function getSearchTextFromFields()
    {
        $sb = SB::create($this->getBaseSearchText());
        $sb->append($this->getUserSearchText());
        $sb->append($this->getRelationsSearchText());

        return $sb->get();
    }

    /**
     * @return string
     */
    protected function getBaseSearchText()
    {
        return SB::create($this->_model->name)->append(' ')
            ->append($this->_model->short_description)->append(' ')
            ->append($this->_model->long_description)->append(' ')
            ->append($this->_model->email)->append(' ')
            ->append($this->_model->phonenumber)->append(' ')
            ->append(date('Y-m-d'))->append(' ')->get();
    }

    /**
     * @return string
     */
    protected function getUserSearchText()
    {
        $sb = SB::create()->append($this->_model->user->name())->append(' ')
            ->append($this->_model->user->address_city)->append(' ');

        $sb->append($this->_model->user->company_name)->append(' ');

        return $sb->get();
    }

    /**
     * @return string
     */
    protected function getRelationsSearchText()
    {
        $relations = ['industries', 'professions', 'skills'];
        $sb = SB::create();

        foreach ($relations as $i => $relation) {
            $relationString = $this->_model->getRelationString($relation);
            $sb->append($relationString)->append($this->getSpaceIfIndexNotEqualsCount($i, count($relations)));
        }

        return $sb->get();
    }

    /**
     * @param int $index
     * @param int $count
     * @return string
     */
    protected function getSpaceIfIndexNotEqualsCount($index, $count)
    {
        if ($index == ($count - 1)) {
            return '';
        }

        return ' ';
    }
}