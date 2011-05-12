<?php
/**
 * @category    Lib
 * @package     BookASP
 * @copyright   Copyright (c) 2010 TENDA Co.,Ltd. (http://www.tenda.co.jp/)
 * @license     http://www.tenda.co.jp/ (C)TENDA Co.,Ltd.
 * @see
 * @deprecated  Zend 認証セッションの拡張
 * @author      ou
 * @filesource
 */
class Lib_Auth_StorageSession extends Zend_Auth_Storage_Session {
    public function __construct($namespace = self::NAMESPACE_DEFAULT, $member = self::MEMBER_DEFAULT, $expirationSeconds = 86400){
        parent::__construct($namespace, $member);
        //セッションタイムアウト値設定
        $this->_session->setExpirationSeconds($expirationSeconds);
    }
}