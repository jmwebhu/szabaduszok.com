<?php

class Model_User_Employer extends Model_User_Abstract
{
    /**
     * @return array
     */
    public function rules()
    {
        $parent     = parent::rules();
        $parent['address_postal_code'][] = ['not_empty'];

        $employer = [
            'address_city'          => [['not_empty'], ['alpha', [':value', true]]],
            'phonenumber'           => [['not_empty']]
        ];

        return array_merge($parent, $employer);
    }

    /**
     * @return int
     */
    public function getType()
    {
        return Entity_User::TYPE_EMPLOYER;
    }

    /**
     * @return Array_Builder
     */
    public function baseSelect()
    {
        $base = parent::baseSelect();
        return $base->where('type', '=', $this->getType());
    }

    /**
     * @return array
     */
    public function getProjects()
    {
        $result = AB::select()->from(new Model_Project())
            ->where('is_active', '=', 1)
            ->and_where('user_id', '=', $this->user_id)
            ->order_by('created_at', 'DESC')->execute()->as_array();

        return $result;
    }

    /**
     * @param $slug
     * @return Model_User_Employer
     */
    public function getBySlug($slug)
    {
        $model = parent::getBySlug($slug);
        return new Model_User_Employer($model->user_id);
    }
}