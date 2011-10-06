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
        Zend_Registry::set('module', $module);
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
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $passList = array(
            'admin' => array('error', 'auth'),
            //'site' => array('index', 'error', 'auth'),
            //'api' => array('error', 'auth'),
        );
        if(isset($passList[$module]) && !in_array($controller, $passList[$module])) {
            $this->_checkLogin($request, $module);
        }
    }

    /**
     * ログインチェック
     */
    private function _checkLogin($request, $module)
    {
        $session = new Lib_App_Session($module);
        $userInfo = $session->getUserInfo();
        //Lib_Util_Log::log($module, 'SESSION ID:'.Lib_App_Session::getId().' userInfo:'.print_r($userInfo, true));
        if(empty($userInfo)) {
            $session->set('requestUri', $request->getRequestUri());
            //$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            //$redirector->gotoUrl('/auth/login')->redirectAndExit();
            //$request->setModuleName('site');
            $request->setControllerName('auth');
            $request->setActionName('login');
        }
    }
}
