<?php

class Gateway_Mailinglist_Mailchimp_Freelancer extends Gateway_Mailinglist_Mailchimp implements ISingleton
{
    /**
     * @var Gateway_Mailinglist_Mailchimp_Freelancer
     */
    private static $_instance = null;

    /**
     * @return bool
     */
    public function subscribe()
    {
        return $this->sendRequest($this->_url . 'lists/' . $this->_config->get('freelancerListId') . '/members',
            $this->getClearedUserData());
    }

    /**
     * @return bool
     */
    public function update()
    {
        return $this->sendRequest(
            $this->_url . 'lists/' . $this->_config->get('freelancerListId') . '/members/' . md5(strtolower($this->_user->getEmail())),
            $this->getClearedUserData(), null, 'PATCH');
    }

    /**
     * @return Gateway_Mailinglist_Mailchimp_Freelancer
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Gateway_Mailinglist_Mailchimp_Freelancer();
        }

        return self::$_instance;
    }

    /**
     * @return array
     */
    protected function getUserData()
    {
        return [
            'email_address'		=> $this->_user->getEmail(),
            'status'			=> self::STATUS_SUBSCRIBED,
            'merge_fields'		=> [
                'FIRSTNAME'		=> $this->_user->getFirstname(),
                'LASTNAME'		=> $this->_user->getLastname(),
                'USER_ID'		=> $this->_user->getUserId(),
                'ZIP'			=> $this->_user->getAddressPostalCode(),
                'CITY'			=> $this->_user->getAddressCity(),
                'STREET'		=> $this->_user->getAddressStreet(),
                'SLUG'			=> $this->_user->getSlug(),
                'HOURLY'		=> $this->_user->getMinNetHourlyWage(),
                'SHORT'			=> $this->_user->getShortDescription(),
                'INDUSTRIES' 	=> $this->_user->getRelationString('industries'),
                'PROFS' 		=> $this->_user->getRelationString('professions'),
                'SKILLS' 		=> $this->_user->getRelationString('skills')
            ]
        ];
    }
}