<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $user = new Entity_User_Freelancer(400);

        /*$data = [
            'firstname'     => 'Martin',
            'lastname'      => 'Joó',
            'email'         => 'martin@szabaduszok.com',
            'address_postal_code'   => '9700',
            'address_city'  => 'Szombathely',
            'min_net_hourly_wage'   => 2500,
            'webpage'       => 'szabaduszok.com',
            'password'      => 'Deth4Life01',
            'password_confirm'      => 'Deth4Life01',
        ];

        $user->submit($data);*/

        $mailinglist = Gateway_Mailinglist_Factory::createMailinglist($user);
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
