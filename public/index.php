<?php
// ルートパス
defined('ROOT_PATH')
    || define('ROOT_PATH', realpath(dirname(__FILE__) . '/..'));

// アプリケーションパスを定義
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', ROOT_PATH . '/application');

// アプリケーション環境を定義
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// rewrite 設定
defined('REWRITE_BASE')
    || define('REWRITE_BASE', (getenv('REWRITE_BASE') ? getenv('REWRITE_BASE') : ''));

// ドメイン名
defined('FQDN')
    || define('FQDN', $_SERVER['SERVER_NAME']);

// 言語コード指定
defined('LANG')
    || define('LANG', 'jp');

// library/ が include_path に設定されることを確保
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(ROOT_PATH . '/library'),
    get_include_path(),
)));

// Zend_Application
require_once 'Zend/Application.php';

/** Smarty 組み込み */
require_once 'Smarty3/SmartyView.php';

// アプリケーションの作成と起動
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);


$application->bootstrap()->run();