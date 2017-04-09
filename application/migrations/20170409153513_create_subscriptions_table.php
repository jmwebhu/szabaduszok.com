<?php defined('SYSPATH') or die('No direct script access.');

class create_subscriptions_table extends Migration
{
    public function up()
    {
        $this->create_table
        (
            'subscriptions',
            [
                'subscription_id' => ['integer', 'unsigned' => true],
                'name'            => ['string[100]'],
                'user_type'       => ['integer'],
                'price'           => ['integer'],
                'usable_products' => ['text'],
                'code_name'       => ['string[255]']
            ]
        );
    }

    public function down()
    {
         $this->drop_table('subscriptions');
    }
}