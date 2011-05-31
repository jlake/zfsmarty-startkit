<?php
/**
 * 共通コントローラ
 * @author ou
 */
class Lib_App_BaseController extends Zend_Controller_Action
{
    protected $_infoFlg;       //共通情報取得のフラグ
    protected $_params;        //リクエスト引数
    protected $_userInfo;      //ユーザー情報
    protected $_paginator;     //ページング用オブジェクト
    /**
     * 初期化
     * @param     boolean  $infoFlg   共通情報取得のフラグ
     * @return void
     */
    public function init($infoFlg = true)
    {
        // リクエストパラメータ
        $this->_params = $this->_getAllParams();
        $this->_infoFlg = $infoFlg;
        if($this->_infoFlg) {
            // ログインユーザ情報
            $namespace = ucwords(Zend_Registry::get('module')) . '_Auth';
            $authSession = new Zend_Session_Namespace($namespace);
            if(isset($authSession->userInfo)) {
            	$this->_userInfo = $authSession->userInfo;
            }
            // ページタイトル先頭文字列指定
            $this->view->headTitle()->setPrefix('My Site');
            $this->view->headTitle()->setSeparator(' - ');
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
     * 
     * ini ファイルかストアー設定の取得
     * @param     string  $storeId     ストアーID
     * @return    string
    */
    protected function _getStoreConfig($storeId)
    {
        try {
            $storeConfig = new Zend_Config_Ini(APPLICATION_PATH.'/configs/store.ini', $storeId);
        } catch(Exception $e) {
            try {
                $storeConfig = new Zend_Config_Ini(APPLICATION_PATH.'/configs/store.ini', 'storeinfo');
            } catch(Exception $e) {
                $storeConfig = new stdClass;
            }
        }
        return $storeConfig;
    }

    /**
     * 
     * DB からストアー情報の取得
     * @param     string  $storeId    ストアーID
     * @return    string
    */
    protected function _getStoreInfo($storeId)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()->from(
                'store_mst'
            )->where(
                "store_id = ?", $storeId
            );
        return $db->fetchRow($select);
    }

    /**
     * Viewにスクリプトの追加
     * @param string $scriptPaths スクリプトのパスの配列
     * @return void
    */
    protected function _appendScripts($scriptPaths = array())
    {
        if(empty($scriptPaths)) return;
        foreach($scriptPaths as $path) {
            $this->view->headScript()->appendFile($path);
        }
    }

    /**
     * ViewにValidation用JavaScriptの追加
     * @return void
     */
    protected function _appendValidationJs()
    {
        $this->_appendScripts(array(
            '/js/plugins/jquery.validate.js',
            '/js/plugins/jquery.alphanumeric.js',
            '/js/custom_validators.js'
        ));
    }

    /**
     * Viewに独自cssの追加
     * @param string $cssPaths スクリプトのパスの配列
     * @return void
    */
    protected function _appendStylesheets($cssPaths = array())
    {
        if(empty($cssPaths)) return;
        foreach($cssPaths as $path) {
            $this->view->headLink()->appendStylesheets($path);
        }
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
        $msg .= '<br/><br/>';
        if($backLink) {
            $msg .= '<a id="go_back" href="'.$backLink.'">戻る</a>';
            $msg .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        if($nextLink) {
            $msg .= '<a href="'.$nextLink.'">次へ</a>';
        }
        $this->view->flashMsg = $msg;
        $this->_helper->viewRenderer->setNoRender();
    }

    /**
     * レイアウト指定
     * @param  $layoutId  レイアウトID
     * @return  void
     **/
    public function _setLayout($layoutId = 'default')
    {
        $this->_helper->layout()->setLayout($layoutId);
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
        }
    }

    /**
     * JSONデータの吐き出す
     * @param  array $data  送信データ
     * @return  void
     **/
    public function _sendJson($data)
    {
        $this->_disableLayout(true);
        $responseData = json_encode($data);
        $this->getResponse()
            ->setHeader('Content-Type', 'text/plain; charset=UTF-8', true)
            ->setBody($responseData);
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
     * @return void
     */
    protected function _setPaginator($dataAdapter, $pageSize = NULL, $pageRange = NULL) {
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
    }

    /**
     * エラーコード指定でエラーメッセージを表示
     * @param string/array $errCd エラーコード
     * @param array $replaceList リプレース内容の配列
     * @param string  $delimeter  区切り文字
     * @return void
     */
    protected function _displaySysError($errCd, $replaceList = array(), $delimeter = '<br />') {
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
        $this->_forward('syserror', 'error', $this->_params['module']);
    }

    /**
     * エラー内容指定でエラーメッセージを表示
     * @param string/array $errMsg エラーメッセージ
     * @param array $replaceList  リプレース内容の配列
     * @param string $delimeter  区切り文字
     * @return void
     */
    protected function _displaySysErrorMsg($errMsg, $replaceList = array(), $delimeter = '<br />') {
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
        $this->_forward('syserror', 'error', $this->_params['module']);
    }

}
