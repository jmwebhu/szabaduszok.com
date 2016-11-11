<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {
        $header = "Content-type: application/json\r\n";
        $header .= "Authorization: Bearer AYSq3RDGsmBLJE-otTkBtM-jBRd1TCQwFf9RGfwddNXWz0uFU9ztymylOhRS\r\n";

        $data = [
            "intent" => "sale",
            "payer" => [
                "payment_method"    => "paypal"
            ],
            "payee" => [
                "email" => "szamlazas@jmweb.hu"
            ],
            "transactions" => [
                "amount" => [
                    "total" => "45000",
                    "currency"  => "HUF"
                ]
            ],
            "description" => "Projekt neve",
            "redirect_urls" => [
                "return_url" => "http://127.0.0.1/szabaduszok.com/success=1",
                "cancel_url" => "http://127.0.0.1/szabaduszok.com/success=0"
            ]
        ];

        $options = [
            'http' => [
                'header'  => $header,
                'method'  => 'POST',
                'content' => json_encode($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $response = file_get_contents("https://api.paypal.com/v1/payments/payment", false, $context);

        echo Debug::vars($response);
    }

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
