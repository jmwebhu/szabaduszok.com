<?php

abstract class Gateway_Mailinglist_Factory
{
    /**
     * @param Entity_User $user
     * @return Gateway_Mailinglist
     */
    public static function createMailinglist(Entity_User $user)
    {
        $gateway = null;

        if ($user instanceof Entity_User_Freelancer) {
            $gateway = Gateway_Mailinglist_Mailchimp_Freelancer::getInstance();
        } else  {
            $gateway = Gateway_Mailinglist_Mailchimp_Employer::getInstance();
        }

        Assert::notNull($gateway);

        $gateway->setUser($user);

        return $gateway;
    }
}