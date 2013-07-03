<?php
set_time_limit(0);

// ルートパス
defined('ROOT_PATH')
    || define('ROOT_PATH', realpath(dirname(__FILE__) . '/..'));
// アプリケーションパスを定義
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', ROOT_PATH . '/application');

// アプリケーション環境を定義
$htaccess = ROOT_PATH . '/docroot/.htaccess';
if(file_exists($htaccess)) {
    foreach(file($htaccess) as $line) {
        $arr = preg_split('/\s+/', trim($line));
        if(strtolower($arr[0]) == 'setenv') {
            define($arr[1], $arr[2]);
        }
    }
}
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// 言語コード指定
defined('LANG')
    || define('LANG', 'jp');

// library/ が include_path に設定されることを確保
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Define path application config ini file */
defined('APPLICATION_INI')
    || define('APPLICATION_INI', APPLICATION_PATH . '/configs/application.ini');


/** Create Zend application */
require_once 'Zend/Application.php';

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_INI
);

$application->bootstrap(array('config', 'dbadapter', 'resource', 'modules', 'filecache'));

/** init logger */
$config =  Zend_Registry::get('config');
$logConfig = $config->log;
$logFolder = 'batch';
$logFile = date('Ymd').'.log';
$logDir = $logConfig->path.'/'.$logFolder;
if(!file_exists($logDir)) {
    mkdir($logDir, 0775, true);
}
$logger = new Zend_Log(new Zend_Log_Writer_Stream("$logDir/$logFile"));
if(isset($config->log->level) && isset(Lib_Util_Log::$logLevel[$config->log->level])) {
    $filter = new Zend_Log_Filter_Priority(Lib_Util_Log::$logLevel[$config->log->level]);
    $logger->addFilter($filter);
}

Zend_Registry::set($logFolder.'_logger', $logger);

Zend_Registry::set('module', 'batch');
