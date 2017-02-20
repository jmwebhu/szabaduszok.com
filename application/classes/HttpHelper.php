<?php

class HttpHelper
{
    public static function sendPost($uri, array $data = [])
    {
        $baseUrl = Kohana::$config->load('base')->get('base_url');
        $url = $baseUrl . '/' . $uri;
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-Requested-With: XMLHttpRequest']);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}