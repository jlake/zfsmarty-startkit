<?php
/**
 * App共通関数
 * @author ou
 */
class Lib_App_Util
{
    /**
     * ヘッダからリモートIPアドレスの取得
     * @return string
     */
    public static function getRemoteAddr()
    {
        if(!empty($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        } else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
}