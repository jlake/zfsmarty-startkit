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
        $request = Zend_Registry::get('request');
        if(empty($request)) $request = new Zend_Controller_Request_Http();
        $forwardFor = $request->getHeader('X-Forwarded-For');
        if(!empty($forwardFor)) {
            return $forwardFor;
        }
        return $_SERVER['REMOTE_ADDR'];
    }
}