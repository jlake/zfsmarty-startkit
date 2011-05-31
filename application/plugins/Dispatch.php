<?php
/**
 * 認証&ルータ プラグイン
 * @author ou
 */
class Plugin_Dispatch extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        // レイアウト指定
        $layout = Zend_Layout::getMvcInstance();
        if ($layout->getMvcEnabled()) {
            $layout->setLayoutPath(APPLICATION_PATH . '/modules/' . $module . '/views/layouts/');
            //$layout->setLayout('default');
        }

        $frontController = Zend_Controller_Front::getInstance();
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $smarty = $viewRenderer->view->getEngine();
        // Smarty コンパイルパス指定
        $smarty->compile_dir .= '/' . $module;
        if(!file_exists($smarty->compile_dir)) {
            mkdir($smarty->compile_dir, 0777, true);
        }
        if($smarty->caching) {  
            // Smarty キャッシュ有効の場合、 キャッシュパス指定
            $smarty->cache_dir .= '/' . $module . '/' .$controller;
            if(!file_exists($smarty->cache_dir)) {
                mkdir($smarty->cache_dir, 0777, true);
            }
        }
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        Zend_Registry::set('module', $module);
        if($module == 'admin') {
            $this->_checkAdminLogin($request);
        } else if($module == 'site') {
            //$this->_checkSiteLogin($request);
        }
    }

    /**
     * 管理者ログインチェック
     */
    private function _checkAdminLogin($request)
    {
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if($controller == 'error' || $controller == 'auth') {
            return true;
        }
        $namespace = ucwords($request->getModuleName()) . '_Auth';
        $storage = new Lib_Auth_StorageSession($namespace, 'storage');
        $data = $storage->read();
        if(!$data || !isset($data->admin_user_id)) {
            $session = new Zend_Session_Namespace($namespace);
            $session->requestUri = $request->getRequestUri();
            $request->setModuleName('admin');
            $request->setControllerName('auth');
            $request->setActionName('login');
            return false;
        }
        return true;
    }

    /**
     * サイトユーザログインチェック
     */
    private function _checkSiteLogin($request)
    {
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if($controller == 'error' || $controller == 'auth') {
            return true;
        }
        $namespace = ucwords($request->getModuleName()) . '_Auth';
        $storage = new Lib_Auth_StorageSession($namespace, 'storage');
        $data = $storage->read();
        if(!$data || !isset($data->user_id)) {
            $session = new Zend_Session_Namespace($namespace);
            $session->requestUri = $request->getRequestUri();
            $request->setModuleName('site');
            $request->setControllerName('auth');
            $request->setActionName('login');
            return false;
        }
        return true;
    }
}