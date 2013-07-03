<?php
/**
 * Site module
 * Dummy コントローラ
 * @author ou
 */
class DummyController extends Lib_App_SiteController
{
    public function init()
    {
        parent::init();
        $this->_setLayout("3column");
    }

    /**
     * Index画面
     */
    public function indexAction()
    {
        $this->_forward('top');
    }

    /**
     * TOP画面（データ一覧、ページング表示）
     */
    public function topAction()
    {
        $scriptPath = $this->view->getScriptPaths();
        Lib_Util_Log::firebug($scriptPath);

        $dummy = new Lib_Db_Table_Dummy();
        $select = $dummy->select()->order('id');
        // ページングのパラメータは Zend_Db_Select オブジェクト （limit, start 不要）
        $this->_setPaginator($select);
    }

    /**
     * データの編集・保存（新規/更新両方対応）
     */
    public function editAction()
    {
        //$params = $this->_getAllParams();
        $params = $this->_params;
        $form = new Site_Form_Dummy();
        if ($this->_request->isPost()) {
            // 保存ボタン押した後処理
            if ($form->isValid($_POST)) {
                // 入力チェック OK の場合、データを保存
                $dummy = new Site_Model_Dummy();
                $dummy->saveData($params);  // DataObjectで
                //$dummy->saveDataByZendDb($params);  // Zend_Db関数で
                //$dummy->saveDataBySQL($params);  // SQLで
                return $this->_redirect('/dummy/index?page='.$params['page']);
            } else {
                // 入力チェック NG の場合、データそのまま返す（エラー情報は Zend_Form にお任せ）
                $form->populate($params);
            }
        } else {
            // データ編集画面表示
            if (isset($params['id']) && $params['id'] != '') {
                $dummy = new Site_Model_Dummy();
                $row = $dummy->getRowData($params);   // DataObjectで
                //$row = $dummy->getRowDataByZendSelect($params);   // Zend_Db_Selectで
                //$row = $dummy->getRowDataBySql($params);   // SQLで
                $form->populate($row);
            }
        }

        $form->setAction($this->view->url());
        $this->view->form = $form;
    }

    /**
     * データの削除
     */
    public function deleteAction()
    {
        //$params = $this->_getAllParams();
        $params = $this->_params;
        if ($this->_request->isPost()) {
            if ($this->_request->getPost('OK')) {
                // 「はい」を選択の場合、削除を行う
                $dummy = new Site_Model_Dummy();
                $dummy->deleteData($params);  // DataObjectで
                //$dummy->deleteDataByZendDb($params);  // Zend_Db関数で
                //$dummy->deleteDataBySql($params);  // SQLで
            }
            return $this->_helper->redirector('index');
        } else {
            // 削除確認
            $dummy = new Site_Model_Dummy();
            $row = $dummy->getRowData($params);   // DataObjectで
            //$row = $dummy->getRowDataByZendSelect($params);   // Zend_Db_Selectで
            //$row = $dummy->getRowDataBySql($params);   // SQLで
            $this->view->row = $row;
        }
    }

    /**
     * JavaScriptテスト
     */
    public function jstestAction()
    {
        $this->_appendValidationJs();
        $this->view->msg = Lib_Util_Message::get('error', 'E0000');
        //$this->view->msg = Lib_Util_Message::get('error', 'E1002', array('value' => '入力した文字列', 'invalid' => '文字列'));
    }

    /**
     * エラー表示テスト
     */
    public function errortestAction()
    {
        $this->_displaySysError('E1002', array('value' => '入力した文字列あああああ', 'invalid' => '文字列'));
        //$this->_displaySysErrorMsg('エラーが発生しています');
    }

    /**
     * ログテスト
     */
    public function logtestAction()
    {
        //Lib_Util_Log::log('site', 'logメッセージ', Zend_Log::DEBUG);
        Lib_Util_Log::log($this->_params['module'], 'logメッセージ', Zend_Log::DEBUG);
        $this->_flashMsg('Logを出力しました。', '/dummy/index');
    }

    /**
     * キャッシュテスト
     */
    public function cachetestAction()
    {
        //$cache = Zend_Registry::get('site_cache');
        $cache = Zend_Registry::get($this->_params['module'].'_cache');
        $cacheKey = 'dummy_cache_test';
        $dateTime = $cache->load($cacheKey);
        if(!$dateTime) {
            $dateTime = date('Y年n月d日 H時i分s秒');
            $cache->save($dateTime, $cacheKey);
        }
        $this->_flashMsg('キャッシュ開始時間：'.$dateTime, '/index', NULL);
    }

    /**
     * JSON 文字列返すテスト
     */
    public function jsontestAction()
    {
        $outData = array(
            'data' => array(
                'a' => "test",
                'b' => array(1,2,3,4)
            ),
            'error' => false
        );
        $this->_sendJson($outData);
    }

    /**
     * Forwardテスト
     */
    public function forwardtestAction()
    {
        $this->_forward('jstest');
    }


    /**
     * 自由Renderテスト
     */
    public function rendertestAction()
    {
        $this->render('test2');
    }

    /**
     * トランザクションのテス
     */
    public function transactiontestAction()
    {
        $params = $this->_params;
        $dummy = new Site_Model_Dummy();
        $dummy->testTransaction($params);  // DataObjectで
        $this->_forward('index');
    }

    /**
     * Smarty機能（自作プラグイン含む）テスト
     */
    public function smartytestAction()
    {
        $this->view->msg = '日本語の短縮処理Smartyプラグインのテストです。';
        $images = array();
        for($i=150; $i<300; $i+=50) {
            $images[] = array(
                'src' => 'sample/dog.jpg',
                'w' => $i,
                'h' => $i
            );
         }
        $this->view->images = $images;
    }

    /**
     * Viewヘルパーテスト
     */
    public function helpertestAction()
    {
    }

    /**
     * メル送信テスト
     */
    public function mailtestAction()
    {
        if ($this->_request->isPost()) {
            if(empty($this->_params['to'])) {
                $this->view->errMsg = 'メールアドレスを入力してください。';
                return;
            }
            Lib_Util_SimpleMail::send(array(
                'from' => 'no-reply@'.FQDN,
                'to' => $this->_params['to'],
                'subject' => 'メール送信テスト',
                'body' => "メール送信テストです。 \n --  %name% \n -- %date%",
                'replace' => array('name' => '欧', 'date' => Lib_Util_Date::getNow())
            ));
            $this->_flashMsg('メールを送信しました。');
        }
    }

    /**
     * ファイルアップロードテスト
     */
    public function uploadtestAction()
    {
        if ($this->_request->isPost()) {
            $config =  Zend_Registry::get('config');
            $uploadConfig = $config->upload;
            $subPath = 'dummy';
            $maxSize = 2048; // kb
            $errMsg = Lib_Util_File::upload('img_file', $uploadConfig->path."/$subPath/", $maxSize, '', '.gif|.jpg|.png');
            if($errMsg) {
                $this->view->errMsg = $errMsg;
            } else {
                $this->view->imgSrc = $subPath . '/' . $_FILES['img_file']['name'];
            }
        }
    }

    /**
     * Soap サーバ (テスト用)
     */
    public function soapserverAction()
    {
        // disable layouts and renderers
        $this->_disableLayout(true);

        // initialize server and set URI
        $server = new Zend_Soap_Server(null, array(
            'uri' => 'http://'.FQDN.'/dummy/soapserver',
            'encoding' => 'utf-8'
        ));

        // set SOAP service class
        $server->setClass('Lib_Soap_TestServer');

        // handle request
        $server->handle();
    }

    /**
     * Soap サーバ WSDL サポート (テスト用)
     */
    public function soapwsdlAction()
    {
        // disable layouts and renderers
        $this->_disableLayout(true);

        // set up WSDL auto-discovery
        $wsdl = new Zend_Soap_AutoDiscover();

        // set SOAP service class
        $wsdl->setClass('Lib_Soap_TestServer');

        // set SOAP action URI
        $wsdl->setUri('http://'.FQDN.'/dummy/soapserver');

        // handle request
        $wsdl->handle();
    }

    /**
     * Soap テスト (クライアント)
     */
    public function soaptestAction()
    {
        $options = array(
            'location' => 'http://'.FQDN.'/dummy/soapserver',
            'uri'      => 'http://'.FQDN.'/dummy/soapserver'
        );
        try {
            $client = new Zend_Soap_Client(null, $options);  
            $allData = $client->getDummyAll();
            $this->_setPaginator($allData);
            $rowData = $client->getDummyRow(1);
            $this->view->rowData = $rowData;
        } catch (SoapFault $s) {
            $this->errMsg = 'SoapFault: [' . $s->faultcode . '] ' . $s->faultstring;
        } catch (Exception $e) {
            $this->errMsg = 'Exception: ' . $e->getMessage();
        }
    }

    /**
     * php情報表示
     */
    public function phpinfoAction()
    {
        phpinfo();
        exit();
    }

}

