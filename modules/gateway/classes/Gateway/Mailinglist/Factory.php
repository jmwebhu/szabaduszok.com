<?php

abstract class Gateway_Mailinglist_Factory
{
    /**
     * @param Entity_User $user
     * @return Gateway_Mailinglist
     */
    public static function createMailinglist(Entity_User $user)
    {
        if ($user instanceof Entity_User_Freelancer) {
            $gateway = Gateway_Mailinglist_Mailchimp_Freelancer::getInstance();
        }

        if ($user instanceof Entity_User_Employer) {
            $gateway = Gateway_Mailinglist_Mailchimp_Employer::getInstance();
        }

        Assert::notNull($gateway);

        $gateway->setUser($user);

        return $gateway;
    }
}