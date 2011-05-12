<?php
/**
 * Site module
 * ユーザ認証 コントローラ
 * @author ou
 */
class AuthController extends Lib_App_SiteController
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
                $this->_params['user_nm'],
                $this->_params['user_pwd']
            );
            $result = $authAdapter->authenticate($authAdapter);
            if($result->isValid()){
                $data = $result->getIdentity();
                $namespace = ucwords(Zend_Registry::get('module')) . '_Auth';
                $auth->setStorage(new Lib_Auth_StorageSession($namespace, 'storage', 3600)); // 有効期限： 1時間
                $storage->write($data);
                if(isset($authSession->requestUri)) {
                    $url = $authSession->requestUri;
                    unset($authSession->requestUri);
                    $this->_redirect($url);
                } else {
                    $this->_redirect('/');
                }
            } else {
                $auth->clearIdentity();
                $this->view->errMsg = "ログインできませんでした、ユーザ名とパスワードを確認してください。";
            }
        }
        $this->_appendValidationJs();
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance(); 
        $auth->clearIdentity();
        $storage->clear();
        $this->_redirect('/');
    }

    public function authfailedAction()
    {
        // 権限がないメッセージを表示
    }

}

