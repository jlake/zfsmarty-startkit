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
                $this->_params['admin_user_nm'],
                $this->_params['admin_user_pwd']
            );
            $result = $auth->authenticate($authAdapter);
            if($result->isValid()){
                $userInfo = $result->getIdentity();
                $this->_session = new Lib_App_Session($this->_params['module']);
                $this->_session->setUserInfo($userInfo);
                $returnUri = $this->_session->get('returnUri');
                if(empty($returnUri)) {
                    $returnUri = '/admin/';
                } else {
                    $this->_session->set('returnUri', null);
                }
                $this->_redirect($returnUri);
            } else {
                $auth->clearIdentity();
                $this->view->error_msg = "ログインできませんでした、ユーザ名とパスワードを確認してください。";
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
        $this->_redirect('/admin/');
    }

    public function authfailedAction()
    {
        // 権限がないメッセージを表示
    }
}
