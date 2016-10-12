<?php

class Text_User
{
    /**
     * @param array $post
     * @return array
     */
    public static function fixPostalCode(array $post)
    {
        $data = $post;
        if (empty(Arr::get($data, 'address_postal_code'))) {
            $data['address_postal_code'] = null;
        }

        return $data;
    }

    /**
     * @param array $post
     * @param string $index
     * @return array
     */
    public static function fixUrl(array $post, $index)
    {
        $data   = $post;
        $url    = Arr::get($data, $index);

        if (!empty($url)) {
            $data[$index] = self::addHttpTo($url);
        }

        return $data;
    }

    /**
     * @param array $post
     * @return array
     */
    public static function alterCheckboxValue(array $post)
    {
        $data       = $post;
        $isCompany  = Arr::get($post, 'is_company', false);

        if ($isCompany == 'on') {
            $data['is_company'] = 1;
        }

        if (!$isCompany || $isCompany == 'off') {
            $data['is_company']     = 0;
            $data['company_name']   = null;
        }

        return $data;
    }

    /**
     * @param string $url
     * @return string
     */
    protected static function addHttpTo($url)
    {
        $urlWithHttp = $url;
        $hasHttp = !(stripos($url, 'http://') === false && stripos($url, 'https://') === false);

        if (!$hasHttp) {
            $urlWithHttp = 'http://' . $url;
        }

        return $urlWithHttp;
    }
}