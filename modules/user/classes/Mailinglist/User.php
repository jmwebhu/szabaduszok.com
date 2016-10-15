<?php

class Mailinglist_User
{
    /**
     * @var Entity_User
     */
    private $_user;

    /**
     * @var Gateway_Mailinglist
     */
    private $_gateway;

    /**
     * Mailinglist_User constructor.
     * @param Entity_User $user
     */
    public function __construct(Entity_User $user)
    {
        $this->_user    = $user;
        $this->_gateway = Gateway_Mailinglist_Factory::createMailinglist($this->_user);
    }

    /**
     * @param int|null $id
     * @return bool
     */
    public function add($id = null)
    {
        if ($id == null) {
            return $this->_gateway->subscribe();
        } else {
            return $this->_gateway->update();
        }
    }
}