<?php

class Model_Subscription extends ORM implements Subscription
{
    protected $_table_name = 'subscriptions';
    protected $_primary_key = 'subscription_id';

    protected $_table_columns = [
        'subscription_id' => ['type' => 'int', 'key' => 'PRI'],
        'name'            => ['type' => 'string'],
        'user_type'       => ['type' => 'int'],
        'price'           => ['type' => 'int'],
        'usable_products' => ['type' => 'string'],
        'hash'            => ['type' => 'string']
    ];

    protected $_has_many = [

    ];

    public function rules()
    {
        return [];
    }

    // -------- SUBSCRIPTION --------

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function price()
    {
        return $this->price;
    }

    /**
     * @return array
     */
    public function usableProducts()
    {
        return $this->usable_products;
    }

    /**
     * @param string $hash
     * @return ORM
     */
    public function byHash($hash)
    {
        return $this->where('hash', '=', $hash)->find();
    }
}
