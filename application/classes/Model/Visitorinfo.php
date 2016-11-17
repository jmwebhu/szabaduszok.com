<?php

class Model_Visitorinfo extends ORM
{
    public function logInfo($source = null, $group = null)
    {

        try
        {           
            $this->vi_ip = $this->getIp();
            $this->vi_os = $this->getOs();
            $this->vi_browser = $this->getBrowser();
            $this->vi_date = date('Y-m-d H:i');
            
            $this->vi_source = $this->getSource($source);
            $groupData = $this->getGroup($group, $source);
            
            $this->vi_group = Arr::get($groupData, 'name');
            $this->vi_group_href = Arr::get($groupData, 'href');
            $this->vi_group_code = Arr::get($groupData, 'code');

            $result = $this->save();
        }
        catch (Exception $ex)
        {
            $result = ['error' => true];
            Log::instance()->addException($ex);
        }

        return $result;
    }

    public function getInfo()
    {
        return [
            'os'        => $this->getOs(),
            'browser'   => $this->getBrowser(),
            'ip'        => $this->getIp()
        ];
    }
    
    /**
     * A kapott string alapjan visszaadja a visitor forras teljes nevet
     * @return string   Forras
     */
    private function getSource($source)
    {
        $realSource = $source;
        switch ($source)
        {
            case 'facebook':
                $realSource = 'facebook.com';
                break;
                
            case 'prog':
                $realSource = 'prog.hu';
                break;
                
            case 'weblabor':
                $realSource = 'weblabor.hu';
                break;
                
            case 'msuadm':
                $realSource = 'Magyar startup adatbázis edm';
                break;
                
            case 'odm':
                $realSource = 'Opten edm';
                break;
        }
        
        return $realSource;
    }
    
    /**
     * A kapott string alapjan visszaadja a Facebook csoport teljes nevet
     * @return array   Forras adatok (nev, hivatkozas)
     */
    private function getGroup($group, $source)
    {
        $realGroup = $group;
        $groupLink = null;
        $groupCode = $group;
        
        if ($source == 'facebook' && !$group) 
        {
            $groupCode = 'unknown';    
        }      
        
        switch ($group) {
            case 'msz':
                $realGroup = 'Mérnök szabadúszók';
                $groupLink = 'https://www.facebook.com/Mérnök-szabadúszók-681919998548642/';
                break;          
                
            case 'mmsz':
                $realGroup = 'Magyar Média Szakemberek (szabadúszók) Csoportja';
                $groupLink = 'https://www.facebook.com/groups/szabaduszok/';
                break;
                
            case 'gym':
                $realGroup = 'Gyakornoki munkák';
                $groupLink = 'https://www.facebook.com/groups/401546213268627/';
                break;  
                
            case 'suv':
                $realGroup = 'Startup vállalkozók';
                $groupLink = 'https://www.facebook.com/groups/startupvallalkozok/';
                break;  
                
            case 'wemf':
                $realGroup = 'Web- és mobilfejlesztők';
                $groupLink = 'https://www.facebook.com/groups/569933236476221/';
                break;  
                
            case 'vsz':
                $realGroup = 'Vállalkozók Szövetsége (VSZ)';
                $groupLink = 'https://www.facebook.com/groups/968361336557757/';
                break;  
                
            case 'ftd':
                $realGroup = 'Fejlesztő, tesztelő diákmunka';
                $groupLink = 'https://www.facebook.com/groups/diakfejleszto/';
                break;
                
            case 'wf':
                $realGroup = 'Webfejlesztés';
                $groupLink = 'https://www.facebook.com/groups/556403331048033/';
                break;
                
            case 'wdewp':
                $realGroup = 'Webdesign és webprogramozás';
                $groupLink = 'https://www.facebook.com/groups/1593310270897000/';
                break;
                
            case 'pa':
                $realGroup = 'Programozói állások';
                $groupLink = 'https://www.facebook.com/groups/1456471327977983/';
                break;
                
            case 'pks':
                $realGroup = 'Programozó kerestetik SOS';
                $groupLink = 'https://www.facebook.com/groups/1166677843373135/';
                break;
                
            case 'p':
                $realGroup = 'Programozók';
                $groupLink = 'https://www.facebook.com/groups/1416001935288558/';
                break;
                
            case 'pa':
                $realGroup = 'Programozói ajánlatok';
                $groupLink = 'https://www.facebook.com/groups/476555639111648/';
                break;
                
            case 'p1':
                $realGroup = 'Programozók';
                $groupLink = 'https://www.facebook.com/groups/408156912634809/';
                break;
                
            case 'kkvucs':
                $realGroup = 'KKV üzleti csoport';
                $groupLink = 'https://www.facebook.com/groups/kkv.uzlet/';
                break;
                
            case 'suk':
                $realGroup = 'StartupKultura';
                $groupLink = 'https://www.facebook.com/groups/startupkultura/';
                break;
                
            case 'pa':
                $realGroup = 'Programozói ajánlatok';
                $groupLink = 'https://www.facebook.com/groups/476555639111648/';
                break;
                
            case 'suh':
                $realGroup = 'Startup huszárok';
                $groupLink = 'https://www.facebook.com/groups/startuphuszarok/';
                break;
                
            case 'igya':
                $realGroup = 'Informatikai gyakornoki állások';
                $groupLink = 'https://www.facebook.com/groups/423224304484853/';
                break;
                
            case 'kwf':
                $realGroup = 'Kezdő webfejlesztők';
                $groupLink = 'https://www.facebook.com/groups/Kezdo.webfejlesztok/';
                break;
                
            case 'suttb':
                $realGroup = 'Startup Tink Tank @Budapest';
                $groupLink = 'https://www.facebook.com/groups/321728904647276/';
                break;
                
            case 'siks':
                $realGroup = 'Sikerember sorozat';
                $groupLink = 'https://www.facebook.com/groups/277742665685261/';
                break;
                
            case 'pt':
                $realGroup = 'Programozás távmunkában';
                $groupLink = 'https://www.facebook.com/groups/580194902118856/';
                break;
        }
        
        return [
            'name' => $realGroup,
            'href' => $groupLink,
            'code' => $groupCode
        ];
    }

    public function getOs() {

        $user_agent     =   Arr::get($_SERVER, 'HTTP_USER_AGENT', '');
        $os_platform    =   "Unknown OS Platform";

        $os_array       =   array(
            '/windows nt 10/i'     =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $os_platform    =   $value;
            }

        }

        return $os_platform;
    }

    public function getBrowser() {

        $user_agent     =   $user_agent     =   Arr::get($_SERVER, 'HTTP_USER_AGENT', '');
        $browser        =   "Unknown Browser";

        $browser_array  =   array(
            '/msie/i'       =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Chrome',
            '/edge/i'       =>  'Edge',
            '/opera/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/mobile/i'     =>  'Mobile Browser'
        );

        foreach ($browser_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $browser    =   $value;
            }

        }

        return $browser;
    }

    public function getIp()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }

    /**
     * Visszaadja az atlagos napi oldal megtekintesek szamat
     *
     * @return int  Atlagos oldalmegtekintesek
     */
    public function getAvgDailyViewCount()
    {
        $counts = $this->getDailyCounts();
        $sum = array_sum($counts);

        return intval($sum / count($counts));
    }

    /**
     * Visszaadja a napi megtekintesek szamat
     *
     * @return array    Napi megtekintesek (minden index egy nap)
     */
    public function getDailyCounts()
    {
        $counts = [];
        $result = DB::select(
            [DB::expr('COUNT(*)'), 'count']
        )->from($this->_table_name)
        ->group_by(DB::expr('DATE_FORMAT(vi_date, "%Y-%m-%d")'))
        ->execute()
        ->as_array();

        foreach ($result as $item)
        {
            $counts[] = Arr::get($item, 'count');
        }

        return $counts;
    }

    public function getAllOsPopularity()
    {
        return DB::select('vi_os', [DB::expr('COUNT(*)'), 'countOfOs'])->from($this->_table_name)->group_by('vi_os')->order_by('countOfOs', 'DESC')->execute()->as_array();
    }

    public function getAllBrowserPopularity()
    {
        return DB::select('vi_browser', [DB::expr('COUNT(*)'), 'countOfBrowser'])->from($this->_table_name)->group_by('vi_browser')->order_by('countOfBrowser', 'DESC')->execute()->as_array();
    }
}