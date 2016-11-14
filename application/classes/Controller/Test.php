<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $header = "Content-type: application/json\r\n";
        $data = [
            "intent"    => "sale",
            "payer"     => [
                "payment_method" => "paypal"
            ],
            "transactions"  => [
                "amount"    => [
                    "total" => 1000,
                    "currency"  => "HUF"
                ]
            ],
            "description"   => "Projekt neve",
            "redirect_urls" => [
                "return_url"    => URL::base(true, false) . "?success=1",
                "cancel_url"    => URL::base(true, false) . "?success=0",
            ]
        ];

        $options = [
            "http" => [
                "header"  => $header,
                "method"  => "POST",
                "content" => json_encode($data)
            ]
        ];

        $url        = "https://api.sandbox.paypal.com/v1/payments/payment";
        $context    = stream_context_create($options);
        $content    = file_get_contents($url, false, $context);

        echo Debug::vars($content);
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
