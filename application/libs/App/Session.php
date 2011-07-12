<?php
/**
 * 共通セッション機能
 * @author ou
 */
class Lib_App_Session
{
    /**
     * モジュール名
     *
     * @var string
     */
    protected $_module;

    /**
     * 有効期限(秒)
     *
     * @var integer
     */
    protected $_expirationSeconds;

    /**
     * コンストラクタ
     *
     * @param string $module  モジュール
     * @param integer $expirationSeconds  有効期限(秒)
     */
    public function __construct($module, $expirationSeconds = 86400) {
        $this->_module = $module;
        $this->_expirationSeconds = $expirationSeconds;
    }

    /**
     * ユーザ認証用セッションを取得
     *
     * @return Zend_Session_Namespace
     */
    public function getAuthSession() {
        $namespace = ucwords($this->_module) . '_Auth';
        $authSession = new Zend_Session_Namespace($namespace);
        $authSession->setExpirationSeconds($this->_expirationSeconds);
        return $authSession;
    }

    /**
     * ユーザ情報の取得
     *
     * @return mixed
     */
    public function getUserInfo() {
        $authSession = $this->getAuthSession();
        return isset($authSession->userInfo) ? $authSession->userInfo : null;
    }

    /**
     * ユーザ情報のセット
     *
     * @return void
     */
    public function setUserInfo($userInfo) {
        $authSession = $this->getAuthSession();
        $authSession->userInfo = $userInfo;
    }
}