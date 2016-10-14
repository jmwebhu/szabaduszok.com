<?php

class Api_Mailservice_Mailchimp_Employer extends Api_Mailservice_Mailchimp
{
    /**
     * @param Model_User $user
     * @return bool
     */
    public function subscribe(Model_User $user)
    {
        return $this->sendRequest($this->_url . 'lists/' . $this->_config->get('projectOwnerListId') . '/members',
            $this->getUserData($user));
    }

    /**
     * @param Model_User $user
     * @return bool
     */
    public function update(Model_User $user)
    {
        return $this->sendRequest(
            $this->_url . 'lists/' . $this->_config->get('projectOwnerListId') . '/members/' . md5(strtolower($user->email)),
            $this->getUserData($user), null, 'PATCH');
    }

    /**
     * @param Model_User $user
     * @return array
     */
    protected function getUserData(Model_User $user)
    {
        return [
            'email_address'		=> $user->email,
            'status'			=> self::STATUS_SUBSCRIBED,
            'merge_fields'		=> [
                'FIRSTNAME'		=> $user->firstname,
                'LASTNAME'		=> $user->lastname,
                'USER_ID'		=> $user->user_id,
                'ZIP'			=> $user->address_postal_code,
                'CITY'			=> $user->address_city,
                'STREET'		=> $user->address_street,
                'PHONE'			=> $user->phonenumber,
                'SLUG'			=> $user->slug,
                'SHORT'			=> $user->short_description,
                'INDUSTRIES' 	=> $user->getRelationString('industries'),
                'PROFS' 		=> $user->getRelationString('professions'),
                'IS_COMPANY'	=> (int) $user->is_company,
                'COMPANY'		=> $user->company_name
            ]
        ];
    }
}