<?php
class Twigextension {

    static function i18n($tag)
    {
        return __($tag);
    }

    static function config($config)
    {
        return Kohana::$config->load($config);
    }

    static function pad($str,$length,$padString,$padOption = STR_PAD_LEFT)
    {
        return str_pad($str,$length,$padString,$padOption);
    }

    static function in_array($search, $array)
    {
        return in_array($search, $array);
    }

    static function quit()
    {
        exit;
    }

    static function dump($var)
    {
        return Debug::vars($var);
    }

    static function path($route_name,$uri = array())
    {
        return Route::i18nPath($route_name,$uri);
    }

    static function url($route_name,$uri = array())
    {
        return Route::i18nUrl($route_name,$uri);
    }

    static function get_user()
    {
        return Auth::instance()->get_user();
    }

    static function logged_in()
    {
        return Auth::instance()->logged_in();
    }

    static function getImage($src,$isCore = false)
    {
        return URL::base(null, false) . 'media/img/' . $src;
    }

    static function getUserfile($src)
    {
        return Kohana::$config->load('settings.userfilesUrl')."/".$src;
    }

    public static function mb_strlen($str)
    {
        return mb_strlen($str);
    }

    public static function mb_substr($str, $start, $end)
    {
        return mb_substr($str,$start,$end);
    }

    public static function shortest($str,$len)
    {
        $strlen = mb_strlen($str);
        if ($strlen <= $len) return $str;

        return mb_substr(strip_tags($str),0,$len)."...";
    }

    public static function shortest_word($str,$len)
    {
        $str = trim(strip_tags($str));

        $result = array();
        $count = 0;
        $words = explode(" ",$str);

        foreach($words as $item)
        {
            if (($count + mb_strlen($item) + 1) < $len) {
                $result[] = $item;
                $count+=mb_strlen($item) + 1;
            } else {
                return join(" ",$result)."...";
            }
        }
    }

    public static function isGranted($mask,ORM $resource,ORM $user = null)
    {
        if (!$user) $user = Auth::instance()->get_user();

        return Access_Control_List::isGranted($mask, $resource, $user);
    }

    public static function hasRole($role,ORM $user = null)
    {
        if (!$user) $user = Auth::instance()->get_user();

        return $user->hasRole($role);
    }

    public static function find_all(ORM $orm,$lang = false)
    {
        return $orm->find_all($lang);
    }

    public static function fuzzy_span($unixtime)
    {
        return Date::fuzzy_span($unixtime);
    }

    public static function renderTemplate($template,array $params = array())
    {
        $twig = Kohana_Twig::instance();
        return $twig->loadTemplate($template)->render($params);
    }

}
