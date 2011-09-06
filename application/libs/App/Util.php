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
        $config =  Zend_Registry::get('config');
        if(isset($config->env) && $config->env->use_proxy) {
            $request = Zend_Registry::get('request');
            if(empty($request)) $request = new Zend_Controller_Request_Http();
            return $request->getHeader('X-Forwarded-For');
        }
        return $_SERVER['REMOTE_ADDR'];
    }
}