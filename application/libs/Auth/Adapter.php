<?php
/**
 * @package    Zend_Translate
 * @deprecated  認証アダプタ
 * @author      ou
 */
class Lib_Auth_Adapter implements Zend_Auth_Adapter_Interface
{

    /**
     * モジュール名
     *
     * @var string
     */
    protected $_module;

    /**
     * ユーザー名
     *
     * @var string
     */
    protected $_username;

    /**
     * パスワード
     *
     * @var string
     */
    protected $_password;


    /**
     * Class constructor
     *
     * @param string $module  モジュール
     * @param string $username  ユーザ名
     * @param string $password  パスワード
     */
    public function __construct($module, $username, $password) {
        $this->_module = $module;
        $this->_username = $username;
        $this->_password = $password;
    }

    /**
     * 認証処理
     *
     * ユーザ名とパスワードを認証する
     *
     * @return Zend_Auth_Result
     */
    public function authenticate() {
        // 返す結果を初期化
        $result = Zend_Auth_Result::FAILURE;
        $identity = null;
        $messages = array();
        if($this->_module == 'admin') {
            // 管理側 ユーザ認証
            $userTable = new Lib_Db_Table_AdminUserMst();
            $select = $userTable->select()
                ->where('admin_user_nm = ?', $this->_username)
                ->where('admin_user_pwd = ?', md5($this->_password));
            $user = $userTable->fetchRow($select);
            if ($user) {
                // 認証成功
                $result = Zend_Auth_Result::SUCCESS;
                $identity = $user;
                //ログイン日時を更新
                $db = $userTable->getAdapter();
                $set = array(
                    'last_login_date' => new Zend_Db_Expr('CURRENT_TIMESTAMP')
                );
                $where = array(
                    $db->quoteInto('admin_user_id = ?', $user['admin_user_id']),
                );
                $userTable->update($set, $where);
            } else {
                // 認証失敗
                $messages[] = 'ユーザ認証に失敗しました。';
            }

        } else if($this->_module == 'site') {
            // サイト側 ローカルテスト用ユーザ認証
            $userTable = new Lib_Db_Table_UserMst();
            $select = $userTable->select()
                ->where('user_nm = ?', $this->_username)
                ->where('user_pwd = ?', md5($this->_password));
            $user = $userTable->fetchRow($select);
            if ($user) {
                // 認証成功
                $result = Zend_Auth_Result::SUCCESS;
                $identity = $user;
                //ログイン日時を更新
                $db = $userTable->getAdapter();
                $set = array(
                    'last_login_date' => new Zend_Db_Expr('CURRENT_TIMESTAMP')
                );
                $where = array(
                    $db->quoteInto('user_id = ?', $user['user_id']),
                );
                $userTable->update($set, $where);
            } else {
                // 認証失敗
                $messages[] = 'ユーザ認証に失敗しました。';
            }
        }

        return new Zend_Auth_Result($result, $identity, $messages);
    }

}