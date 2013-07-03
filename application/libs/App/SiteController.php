<?php
/**
 * 共通コントローラ(サイト側)
 * @author ou
 */
class Lib_App_SiteController extends Lib_App_BaseController
{
    /**
     * 初期化
     * @param     boolean  $infoFlg   共通情報取得のフラグ
     * @return void
     */
    public function init($infoFlg = true)
    {
        parent::init($infoFlg);
        if($this->_infoFlg) {
            // ページタイトル先頭文字列指定
            $this->view->headTitle()->setPrefix('My Site');
            $this->view->headTitle()->setSeparator(' - ');
        }

        $cookieTest = $this->_getCookie('cookieTest', true);
        //Lib_Util_Log::log('site', $this->_request->getRequestUri()."\ncookieTest=".print_r($cookieTest, 1));
        if(empty($cookieTest)){
            $cookieTest = array(
                'access_id' => zend_Session::getId() .'_'. time(),
                'access_cnt' => 1,
            );
        } else {
            $cookieTest['access_cnt'] += 1;
        }
        $this->_setCookie('cookieTest', $cookieTest, 3600, '/');
    }
}
