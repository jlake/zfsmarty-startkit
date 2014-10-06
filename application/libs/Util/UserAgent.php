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
    public static function isFromDocomoStore()
    {
        if(preg_match('/DOCOMO\/2.0\s[\w-]*\(ST;/i', $_SERVER['HTTP_USER_AGENT'])){
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
    public static function getAndroidDeviceCode()
    {
        if(preg_match('/\s([\w-\s]+)\s*(\(ST;|Build\/)/i', $_SERVER['HTTP_USER_AGENT'], $matches)){
            return preg_replace('/SonyEricsson/i', '', trim($matches[1]));
        }
        return '';
    }

    /**
     * Android OSバージョンの取得
     *
     * @param   $numOnly
     * @return  string
     */
    public static function getAndroidVersion($numOnly = false)
    {
        if(preg_match("/Android\s+([0-9.]*?);/", $_SERVER['HTTP_USER_AGENT'], $matches)) {
            return $matches[1];
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
        $crawlers= array(
            '/mixi-check/i',
            '/facebookexternalhit/i',
            /*
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
            if(preg_match($c, $_SERVER['HTTP_USER_AGENT'])) {
                return true;
            }
        }
        return false;
    }
}
