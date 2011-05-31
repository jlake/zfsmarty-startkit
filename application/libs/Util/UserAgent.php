<?php
/**
 * User-Agentで機種判別機能
 * @author ou
 *
 */
class Lib_Util_UserAgent {
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
        $isMobile = preg_match($pattern1, $ua) || preg_match($pattern2, $ua);
    }

    /**
     * DoCoMo端末の判別
     *
     * @param   なし
     * @return  boolean
     */
    public static function isDocomo()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
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
        if(preg_match('/(^UP.Browser|^KDDI)/i', $_SERVER['HTTP_USER_AGENT'])){
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
        if(preg_match('/(^J-PHONE|^Vodafone|^SoftBank)/i', $_SERVER['HTTP_USER_AGENT'])){
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
        if(preg_match('/Mozilla\/.*(iPhone)/i', $_SERVER['HTTP_USER_AGENT'])){
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
        if(preg_match('/Mozilla\/.*(Android)/i', $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
        return false;
    }
}