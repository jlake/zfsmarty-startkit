<?php
/**
 * Log処理クラス
 *
 */
class Lib_Util_Log
{
    public static $logLevel = array(
        'EMERG'   => 0,  // 緊急事態 (Emergency): システムが使用不可能です
        'ALERT'   => 1,  // 警報 (Alert): 至急対応が必要です
        'CRIT'    => 2,  // 危機 (Critical): 危機的な状況です
        'ERR'     => 3,  // エラー (Error): エラーが発生しました
        'WARN'    => 4,  // 警告 (Warning): 警告が発生しました
        'NOTICE'  => 5,  // 注意 (Notice): 通常動作ですが、注意すべき状況です
        'INFO'    => 6,  // 情報 (Informational): 情報メッセージ
        'DEBUG'   => 7,  // デバッグ (Debug): デバッグメッセージ
    );

    protected $_logger;

    public function __construct($logFolder = 'site')
    {
        $this->_logger = self::getLogger($logFolder);
    }

    /**
     * システムLoggerの取得(存在しない場合は作成)
     *
     * @param string  $logFolder ログフォルダ（admin, site, batch など）
     * @return  Boolean
     */
    public static function getLogger($logFolder = 'site')
    {
        $config = Zend_Registry::get('config');
        try {
            $logger = Zend_Registry::get($logFolder . '_logger');
        } catch(Exception $e) {
            $logPath = $config->log->path.'/'.$logFolder;
            $logFile = date('Ymd').'.log';
            if(!file_exists($logPath)) {
                mkdir($logPath, 0775, true);
            }
            $logger = new Zend_Log(new Zend_Log_Writer_Stream("$logPath/$logFile"));
            Zend_Registry::set($folder.'_logger', $logger);
        }
        if(isset($config->log->level) && isset(self::$logLevel[$config->log->level])) {
            $filter = new Zend_Log_Filter_Priority(self::$logLevel[$config->log->level]);
            $logger->addFilter($filter);
        }
        return $logger;
    }

    /**
     * 汎用ログ出力（new 不要）
     *  例)  Lib_Util_Log::log('site', 'logメッセージ', Zend_Log::DEBUG);
     *
     * @param string  $logFolder ログフォルダ（admin, site, batch など）
     * @param string  $logMsg ログ内容
     * @param string  $level Zend_Logのレベル
     * @return  unknown
     */
    public static function log($logFolder, $logMsg, $level=Zend_Log::INFO)
    {
        // iniファイルの設定でログフィルタ
        $logger = self::getLogger($logFolder);
        if($logger) {
            return $logger->log($logMsg, $level);
        }
    }

    /**
     * ログ追加（出力ログレベル指定可能）
     *
     * @param   string  $logMsg  ログ出力文字列
     * @param   string  $level   ログ出力レベル
     * @return  Boolean
     */
    public function append($logMsg, $level=Zend_Log::INFO)
    {
        return $this->_logger->log($logMsg, $level);
    }

    /**
     * デバッグログ出力
     *
     * @param   string  $logMsg  ログ出力文字列
     * @return  Boolean
     */
    public function debug($logMsg)
    {
        return $this->_logger->debug($logMsg);
    }

    /**
     * エラーログ出力
     *
     * @param   string  $logMsg  ログ出力文字列
     * @return  Boolean
     */
    public function error($logMsg)
    {
        return $this->_logger->err($logMsg);
    }

    /**
     * FireBug出力
     *
     * @param   string  $logMsg  ログ出力文字列
     * @return  Boolean
     */
    public static function firebug($logMsg, $level=Zend_Log::INFO)
    {
        try {
            $logger = Zend_Registry::get('firebug');
        } catch(Exception $e) {
            $logger = new Zend_Log(new Zend_Log_Writer_Firebug());
            Zend_Registry::set('firebug', $logger);
        }
        $logger->log($logMsg, $level);
    }
}