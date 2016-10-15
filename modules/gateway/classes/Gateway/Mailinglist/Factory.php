<?php

use Szabaduszok\Assert;

abstract class Gateway_Mailinglist_Factory
{
    /**
     * @param Entity_User $user
     * @return Gateway_Mailinglist
     */
    public static function createMailinglist(Entity_User $user)
    {
        if ($user instanceof Entity_User_Freelancer) {
            return Gateway_Mailinglist_Mailchimp_Freelancer::getInstance();
        }

        if ($user instanceof Entity_User_Employer) {
            return Gateway_Mailinglist_Mailchimp_Employer::getInstance();
        }

        Assert::neverShouldReachHere();
    }
}