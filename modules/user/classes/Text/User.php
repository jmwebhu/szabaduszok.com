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
    public static function alterCheckboxCompanyValue(array $post)
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
     * @param array $post
     * @return array
     */
    public static function alterCheckboxAbleToBillValue(array $post)
    {
        $data = $post;
        $isAbleToBill  = Arr::get($post, 'is_able_to_bill', false);

        $data['is_able_to_bill'] = ($isAbleToBill == 'on') ? 1 : 0;

        return $data;
    }

    /**
     * @param string $url
     * @return string
     */
    protected static function addHttpTo($url)
    {
        $urlWithHttp    = $url;
        $hasHttp        = !(stripos($url, 'http://') === false && stripos($url, 'https://') === false);

        if (!$hasHttp) {
            $urlWithHttp = 'http://' . $url;
        }

        return $urlWithHttp;
    }

    /**
     * @param $post
     * @return mixed
     */
    public static function fixProfessionalExperience($post)
    {
        $original = Arr::get($post, 'professional_experience', '');
        $post['professional_experience'] = str_replace(',', '.', $original);

        return $post;
    }
}