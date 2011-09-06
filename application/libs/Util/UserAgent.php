<?php
/**
 * User-Agentで機種判別機能
 * @author ou
 *
 */
class Lib_Util_UserAgent
{
    /**
     * モバイルかどうかの判別
     *
     * @param   なし
     * @return  boolean
     */
    public static function isMobile()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $pattern1 = '/Profile\/MIDP-\d/i';
        $pattern2 = '/Mozilla\/.*(SymbianOS|iPhone|iPod|iPad|Android|Windows\sCE)/i';
        return preg_match($pattern1, $ua) || preg_match($pattern2, $ua);
    }

    /**
     * DoCoMo端末の判別
     *
     * @param   なし
     * @return  boolean
     */
    public static function isDocomo()
    {
        if(preg_match('/^DoCoMo/i', $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
        return false;
    }

    /**
     * KDDI端末の判別
     *
     * @param   なし
     * @return  boolean
     */
    public static function isKddi()
    {
        if(preg_match('/^(UP.Browser|KDDI)/i', $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
        return false;
    }

    /**
     * Softbank端末の判別
     *
     * @param   なし
     * @return  boolean
     */
    public static function isSoftbank()
    {
        if(preg_match('/^(J-PHONE|Vodafone|SoftBank)/i', $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
        return false;
    }

    /**
     * iPhone端末の判別
     *
     * @param   なし
     * @return  boolean
     */
    public static function isIPhone()
    {
        if(preg_match('/iPhone/i', $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
        return false;
    }

    /**
     * Android端末の判別
     *
     * @param   なし
     * @return  boolean
     */
    public static function isAndroid()
    {
        if(preg_match('/Android/i', $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
        return false;
    }

    /**
     * Docomoストアアプリの判別
     *
     * @param   なし
     * @return  boolean
     */
    public static function fromDocomoStore()
    {
        if(preg_match('/DOCOMO\/2.0\s[a-z0-9\-]*\(ST;/i', $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
        return false;
    }

    /**
     * Android端末コードの取得
     *
     * @param   なし
     * @return  string
     */
    public static function getAndriodDevCd()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $ua = preg_replace('/SonyEricsson/', '', $ua);
        if (self::fromDocomoStore()) {
            if(preg_match('/\s+([\w-]+)\s*\(ST\;/i', $ua, $matches)){
                return $matches[1];
            }
        } else {
            if(preg_match('/;\s+([\w-]+)\s+Build\/\w+/i', $ua, $matches)){
                return $matches[1];
            }
        }
        return '';
    }

    /**
     * Android OSの取得
     *
     * @param   なし
     * @return  string
     */
    public static function getAndriodOsVer()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if (self::fromDocomoStore()) {
            $checkKey = 0;
            $arrTmp  = split(";", trim($ua));
            foreach ($arrTmp as $key => $tmpUA) {
                if ($tmpUA == 'Android') {
                    $checkKey = $key + 1;
                    return $arrTmp[$checkKey];
                }
            }
        } else {
            $arrTmp  = split(";", trim($ua));
            foreach ($arrTmp as $tmpVal) {
                if (preg_match("/\bAndroid\b/", $tmpVal)) {
                    $arrOs = split(" ", trim($tmpVal));
                    return $arrOs[count($arrOs)-1];
                }
            }
        }
        return '';
    }

    /**
     * クローラーの判別
     *
     * @param   なし
     * @return  boolean
     */
    public static function isCrawler()
    {
        if(ini_get('browscap')) {
            $browser= get_browser(NULL, true);
            if($browser['crawler']) {
                return true;
            }
        } else {
            $agent= $_SERVER['HTTP_USER_AGENT'];
            $crawlers= array(
                '/Googlebot/',
                '/Yahoo!\sSlurp/',
                '/msnbot/',
                '/Mediapartners-Google/',
                '/Y!J-SRD/',
                '/Y!J-MBS/',
                '/Y!J-BSC/',
                '/YahooFeedSeeker/',
                '/LD_mobile_bot/',
                '/Baiduspider/i',
                '/BaiduMobaider/i',
                '/Ask\sJeeves/',
                '/Ask.jp/',
                /*
                '/Scooter/',
                '/Yahoo-MMCrawler/',
                '/FAST-WebCrawler/',
                '/Yahoo-MMCrawler/',
                '/FAST-WebCrawler/',
                '/FAST Enterprise Crawler/',
                '/grub-client-/',
                '/MSIECrawler/',
                '/NPBot/',
                '/NameProtect/i',
                '/ZyBorg/i',
                '/worio bot heritrix/i',
                '/libwww-perl/i',
                '/Gigabot/i',
                '/bot@bot.bot/i',
                '/SeznamBot/i',
                */
            );
            foreach($crawlers as $c) {
                if(preg_match($c, $agent)) {
                    return true;
                }
            }
        }
        return false;
    }
}