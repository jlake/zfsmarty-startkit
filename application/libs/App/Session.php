<?php
/**
 * 共通セッション機能
 * @author ou
 */
class Lib_App_Session
{
    /**
     * セッションオブジェクト（ネームスペース）
     *
     * @var Zend_Session_Namespace
     */
    protected $_session;

    /**
     * コンストラクタ
     *
     * @param string $module  モジュール
     * @param int $expirationSeconds  有効期限(秒, 省略可)
     */
    public function __construct($module, $expirationSeconds = null)
    {
        $rootName = ucwords($module) . '_Root';
        $this->_session = new Zend_Session_Namespace($rootName);
        if(isset($expirationSeconds)) {
            $this->_session->setExpirationSeconds($expirationSeconds);
        }
    }

    /**
     * キー指定でセッション内容を取得
     *
     * @param string $key  キー
     * @param mixed $default  デフォールトバリュー
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->_session->$key) ? $this->_session->$key : $default;
    }

    /**
     * キー指定でセッション内容を取得
     *
     * @param string $key  キー
     * @param mixed $value  バリュー
     * @return mixed
     */
    public function set($key, $value)
    {
        $this->_session->$key = $value;
    }

    /**
     * ユーザ情報を取得
     *
     * @return mixed
     */
    public function getUserInfo()
    {
        return $this->get('userInfo');
    }

    /**
     * ユーザ情報をセットする
     *
     * @param mixed $userInfo  ユーザ情報
     * @return void
     */
    public function setUserInfo($userInfo)
    {
        $this->set('userInfo', $userInfo);
    }
}