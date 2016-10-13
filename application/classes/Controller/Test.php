<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $entityEmployer = new Entity_User_Employer();
        $data = [
            'lastname'  => 'Joó',
            'firstname' => 'Martin',
            'email'     => 'joomartin8@jmweb.hu',
            'password'  => 'Deth4Life01',
            'password_confirm'  => 'Deth4Life01',
            'address_postal_code'   => '',
            'address_city'  => 'Szombathely',
            'address_street'    => 'Engels',
            'phonenumber'   => '06301923380',
            'is_company'    => 'on',
            'company_name'  => 'Jmweb Zrt.'
        ];

        $entityEmployer->submit($data);
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
