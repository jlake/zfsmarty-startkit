<?php
/**
 * 共通コントローラ
 * @author ou
 */
class Lib_App_BaseController extends Zend_Controller_Action
{
    protected $_infoFlg;       //共通情報取得のフラグ
    protected $_params;        //リクエスト引数
    protected $_session;       //セッションオブジェクト
    protected $_userInfo;      //ユーザー情報
    protected $_paginator;     //ページング用オブジェクト
    /**
     * 初期化
     * @param     boolean  $infoFlg   共通情報取得のフラグ
     * @return void
     */
    public function init($infoFlg = true)
    {
        Zend_Registry::set('request', $this->_request);
        // リクエストパラメータ
        $this->_params = $this->_getAllParams();
        $this->_infoFlg = $infoFlg;
        if($this->_infoFlg) {
            // ログインユーザ情報
            $this->_session = new Lib_App_Session($this->_params['module']);
            $this->_userInfo = $this->_session->getUserInfo();
            $this->_appendJs(REWRITE_BASE . '/js/jquery-1.7.2.min.js');
        }
    }

    /**
     * 画面表示前処理
     * @return void
     */
    public function postDispatch()
    {
        if($this->_infoFlg) {
            // リクエストパラメータをビューに渡す
            $this->view->params = $this->_params;
            // ユーザ情報をビューに渡す
            $this->view->userInfo = $this->_userInfo;
        }          
    }

    /**
     * Cookie設定
     * @param string $key Cookieキー
     * @param string/array $value  Cookie値
     * @param integer $lifeTime  有効期限(秒)
     * @param integer $path  パス
     * @param integer $domain  ドメイン
     * @return void
     */
    protected function _setCookie($key, $value, $lifeTime = NULL, $path = '', $domain = '')
    {
        if(is_array($value)) {
            $value = json_encode($value);
        }
        $expires = empty($lifeTime) ? 0 : $_SERVER['REQUEST_TIME'] + $lifeTime;
        setcookie($key, $value, $expires, $path, $domain);
        /*
        $cookie = $key. '=' . $value;
        if(!empty($lifeTime)) {
            $expireDt = new Zend_Date($_SERVER['REQUEST_TIME'] + $lifeTime);
            $cookie .= '; expires=' . $expireDt->get(Zend_Date::COOKIE);
        }
        if(!empty($path)) {
            $cookie .= '; path=' . $path;
        }
        if(!empty($domain)) {
            $cookie .= '; domain=' . $domain;
        }
        //error_log('Set-Cookie: '.$cookie);
        $this->getResponse()->setHeader('Set-Cookie', $cookie, true);
        */
    }

    /**
     * Cookie取得
     * @param string $key Cookieキー
     * @param boolean $decodeFlg  デコードフラグ
     * @return void
     */
    protected function _getCookie($key, $decodeFlg = false)
    {
        $value = $this->getRequest()->getCookie($key);
        if($decodeFlg && !empty($value)) {
            $value = json_decode($value, true);
        }
        return $value;
    }

    /**
     * Viewにスクリプトの追加
     * @param mixed $scriptPath スクリプトのパス(配列可)
     * @return void
    */
    protected function _appendJs($scriptPath = array())
    {
        if(empty($scriptPath)) return;
        if(is_array($scriptPath)) {
            foreach($scriptPath as $path) {
                $this->view->headScript()->appendFile($path, 'text/javascript', array('charset'=>'utf-8'));
            }
        } else {
            $this->view->headScript()->appendFile($scriptPath, 'text/javascript', array('charset'=>'utf-8'));
        }
    }

    /**
     * Viewに独自cssの追加
     * @param mixed $cssPath スクリプトのパス(配列可)
     * @return void
    */
    protected function _appendCss($cssPath = array())
    {
        if(empty($cssPath)) return;
        if(is_array($cssPath)) {
            foreach($cssPath as $path) {
                $this->view->headLink()->appendStylesheet($path);
            }
        } else {
            $this->view->headLink()->appendStylesheet($cssPath);
        }
    }

    /**
     * ViewにValidation用JavaScriptの追加
     * @return void
     */
    protected function _appendValidationJs()
    {
        $this->_appendJs(array(
            REWRITE_BASE . '/js/plugins/jquery.validate.js',
            REWRITE_BASE . '/js/plugins/jquery.alphanumeric.js',
            REWRITE_BASE . '/js/custom_validators.js'
        ));
    }

    /**
     * 簡単メッセージの表示
     * @param string $msg メッセージ
     * @param number $seconds 表示秒数
     * @param string $nextLink 次へのリンクＵＲＬ
     * @param string $backLink 戻るのリンクＵＲＬ
     * @return void
     */
    protected function _flashMsg($msg, $seconds = 5, $nextLink = '', $backLink = '')
    {
        if($nextLink && is_numeric($seconds) && $seconds > 0) {
            $script = "window.onload = function() {
                var delay = $seconds * 1000;
                setTimeout(function(){
                    document.location.href= '$nextLink';
                }, delay);
            }";
            $this->view->headScript()->appendScript($script);
            $msg .= "<br/><br/>{$seconds}秒後自動リダイレクトします...";
        }
        $msg .= '<br/><center>';
        if($backLink) {
            $msg .= '<a id="go_back" href="'.$backLink.'">戻る</a>';
            $msg .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        if($nextLink) {
            $msg .= '<a href="'.$nextLink.'">次へ</a>';
        }
        $msg .= '</center>';
        $this->view->flashMsg = $msg;
        $this->_helper->viewRenderer->setNoRender();
    }

    /**
     * レイアウト指定
     * @param  $layoutId  レイアウトID
     * @param  $enabled  レイアウト有効フラグ
     * @return  void
     **/
    public function _setLayout($layoutId = 'default', $enabled = true)
    {
        $this->_helper->layout()->setLayout($layoutId, $enabled);
    }

    /**
     * レイアウト使わない
     * @return  void
     **/
    public function _disableLayout($noRenderer = false)
    {
        $this->_helper->layout()->disableLayout();
        if($noRenderer) {
            $this->_helper->viewRenderer->setNoRender();
            $front = Zend_Controller_Front::getInstance();
            if($front->hasPlugin('ZFDebug_Controller_Plugin_Debug')) {
                $front->unregisterPlugin('ZFDebug_Controller_Plugin_Debug');
            }
        }
    }

    /**
     * JSONデータ吐き出す
     * @param  array $data  送信データ
     * @param  string $charset 文字コード
     * @return  void
     **/
    public function _sendJson($data, $charset='UTF-8')
    {
        $this->_disableLayout(true);
        $this->getResponse()
            ->setHeader('Content-Type', "application/json; charset=$charset", true)
            ->setBody(json_encode($data));
    }

    /**
     * XMLデータ吐き出す
     * @param  mixed $data  送信データ
     * @param  string $charset 文字コード
     * @return  void
     **/
    public function _sendXml($data, $charset='UTF-8')
    {
        if(is_array($data)) {
            $data = Lib_Util_Array::toXml($data);
        }
        $this->_disableLayout(true);
        $this->getResponse()
            ->setHeader('Content-Type', "text/xml; charset=$charset", true)
            ->setBody($data);
    }

    /**
     * テキスト形式のデータ吐き出す
     * @param  mixed $data  送信データ
     * @param  string $charset 文字コード
     * @param  string $newLine 改行コード
     * @return  void
     **/
    public function _sendPlainText($data, $charset='UTF-8', $newLine = "\r\n")
    {
        $responseText = '';
        if(is_array($data)) {
            foreach($data as $key => $value) {
                $responseText .= $key.'='.$value.$newLine;
            }
        } else {
            $responseText = $data;
        }
        $this->_disableLayout(true);
        $this->getResponse()
            ->setHeader('Content-Type', "text/plain; charset=$charset", true)
            ->setBody($responseText);
    }

    /**
     * 権限認証失敗時処理
     * @return void
     */
    protected function _authFailed()
    {
        $this->_forward('authfailed', 'auth');
    }

    /**
     * ページング設定
     * @param array/object $dataAdapter ページング対象データアダプタ
     * @param int $pageSize ページサイズ（表示アイテム最大件数）
     * @param int $pageRange ページリンク最大件数
     * @return void
     */
    protected function _setPaginator($dataAdapter, $pageSize = NULL, $pageRange = NULL)
    {
        $url = $this->_request->getRequestUri();
        $url = preg_replace('/(\/page\/\d+)|([&\?]page=\d+)/i', '', $url);
        $url .= (strpos($url, '?') === FALSE) ? '?' : '&';
        $this->view->baseUrl = $url;

        if(!isset($pageSize)) {
            $pageSize = $this->view->options['pageSize'];
        }
        if(!isset($pageRange)) {
            $pageRange = $this->view->options['pageRange'];
        }
        $this->_paginator = Zend_Paginator::factory($dataAdapter);
        $this->_paginator->setItemCountPerPage($pageSize);
        $this->_paginator->setPageRange($pageRange);
        $this->_paginator->setCurrentPageNumber($this->_getParam('page'), 1);

        $this->view->paginator = $this->_paginator;

        //ページングスタイル指定
        $this->_setPaginatorTemplate('partials/default_paginator.html');
    }

    /**
     * ページングテンプレート指定
     * @param string $template テンプレート
     * @param string $scrollingStyle スクロールスタイル
     * @return void
     */
    protected function _setPaginatorTemplate($template, $scrollingStyle = NULL)
    {
        //ページングスタイル指定
        Zend_View_Helper_PaginationControl::setDefaultViewPartial($template);
        if(!empty($scrollingStyle)) {
            Zend_Paginator::setDefaultScrollingStyle($scrollingStyle);
        }
    }

    /**
     * ページングキャッシュを有効にする
     * @param string $lifeTime キャッシュ時間(秒)
     * @return void
     */
    protected function _enablePaginatorCache($lifeTime = 300)
    {
        $config =  Zend_Registry::get('config');
        $memcacheConfig = $config->memcache;
        $frontendOptions = array(
            'caching' => true,
            'lifetime' => $lifeTime,
            'automatic_serialization' => true
        );
        $servers = array();
        foreach($memcacheConfig->servers as $name => $serverConfig) {
            $servers[] = $serverConfig->toArray();
        }
        $backendOptions = array(
            'servers' => $servers,
            'compression' => isset($memcacheConfig->compression) ? $memcacheConfig->compression : false
        );
        $cache = Zend_Cache::factory('Core', 'Memcached', $frontendOptions, $backendOptions);
        Zend_Paginator::setCache($cache);
    }

    /**
     * エラーコード指定でエラーメッセージを表示
     * @param string/array $errCd エラーコード
     * @param array $replaceList リプレース内容の配列
     * @param string  $delimeter  区切り文字
     * @return void
     */
    protected function _displaySysError($errCd, $replaceList = array(), $delimeter = '<br />')
    {
        $message = '';
        if(is_array($errCd)) {
            $msgArr = array();
            foreach($errCd as $cd) {
                $msgArr[] = Lib_Util_Message::get('error', $cd);
            }
            $message = join($delimeter, $msgArr);
        } else {
            $message = Lib_Util_Message::get('error', $errCd);
        }
        if(!empty($replaceList)) {
            $message = Lib_Util_Message::replace($message, $replaceList);
        }
        $this->view->message = $message;
        $this->_forward('syserror', 'error', 'site');
    }

    /**
     * エラー内容指定でエラーメッセージを表示
     * @param string/array $errMsg エラーメッセージ
     * @param array $replaceList  リプレース内容の配列
     * @param string $delimeter  区切り文字
     * @return void
     */
    protected function _displaySysErrorMsg($errMsg, $replaceList = array(), $delimeter = '<br />')
    {
        $message = '';
        if(is_array($errMsg)) {
            $msgArr = array();
            foreach($errMsg as $msg) {
                $msgArr[] = $msg;
            }
            $message = join($delimeter, $msgArr);
        } else {
            $message = $errMsg;
        }
        if(!empty($replaceList)) {
            $message = Lib_Util_Message::replace($message, $replaceList);
        }
        $this->view->message = $message;
        $this->_forward('syserror', 'error', 'site');
    }

}
