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
        parent::init(false);
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
                $this->_params['user_nm'],
                $this->_params['user_pwd']
            );
            $result = $authAdapter->authenticate($authAdapter);
            if($result->isValid()){
                $userInfo = $result->getIdentity();
                $this->_session = new Lib_App_Session($this->_params['module']);
                $this->_session->setUserInfo($userInfo);
                $requestUri = $this->_session->get('requestUri');
                if(isset($requestUri)) {
                    $this->_session->set('requestUri', null);
                    $this->_redirect($requestUri);
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
        $this->_disableLayout(true);
        $auth = Zend_Auth::getInstance(); 
        $auth->clearIdentity();
        $this->_session = new Lib_App_Session($this->_params['module']);
        $this->_session->setUserInfo(null);
        $this->_redirect('/');
    }

    public function authfailedAction()
    {
        // 権限がないメッセージを表示
    }

}

