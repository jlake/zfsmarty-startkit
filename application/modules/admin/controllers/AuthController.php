<?php
/**
 * Admin module
 * ユーザ認証 コントローラ
 * @author ou
 */
class Admin_AuthController extends Lib_App_AdminController
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
    }

    public function loginAction()
    {
        if($this->getRequest()->isPost()){
            $auth = Zend_Auth::getInstance();
            $authAdapter = new Lib_Auth_Adapter(
                $this->_params['module'],
                $this->_params['admin_user_nm'],
                $this->_params['admin_user_pwd']
            );
            $result = $auth->authenticate($authAdapter);
            if($result->isValid()){
                $data = $result->getIdentity();
                $namespace = ucwords(Zend_Registry::get('module')) . '_Auth';
                $auth->setStorage(new Lib_Auth_StorageSession($namespace, 'storage', 86400)); // 有効期限： 1日
                $storage->write($data);
                if(isset($authSession->requestUri)) {
                    $url = $authSession->requestUri;
                    unset($authSession->requestUri);
                    $this->_redirect($url);
                } else {
                    $this->_redirect('/admin/');
                }
            } else {
                $auth->clearIdentity();
                $this->view->error_msg = "ログインできませんでした、ユーザ名とパスワードを確認してください。";
            }
        }
        $this->_appendValidationJs();
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance(); 
        $auth->clearIdentity();
        $this->_redirect('/admin/');
    }

    public function authfailedAction()
    {
        // 権限がないメッセージを表示
    }

}

