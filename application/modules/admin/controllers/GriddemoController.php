<?php
/**
 * Admin module
 * Grid demo コントローラ
 * @author ou
 */
class Admin_GriddemoController extends Lib_App_AdminController
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $this->_forward('demo1');
    }

    public function demo1Action()
    {
        $this->_useJqGrid();
    }

    public function demo1dataAction()
    {
        $demoObj = new Admin_Model_GridDemo();
        $data = $demoObj->getDummyData($this->_params);
        $this->_sendJson($data);
    }

    public function demo2Action()
    {
        $this->_useJqGrid();
        $this->_useJqueryUI(null);
    }

    public function demo2dataAction()
    {
        $demoObj = new Admin_Model_GridDemo();
        $data = $demoObj->getDummyData($this->_params);
        $this->_sendJson($data);
    }

    public function editdataAction()
    {
        $result = '';
        try {
            $dataObj = new Lib_Db_DataObject(array(
                'table' => new Lib_Db_Table_Dummy
            ));
            switch($this->_params['oper']) {
                case 'add':
                    $dataObj->save(array(
                        'id' => NULL,
                        'inf1' => $this->_params['inf1'],
                        'inf2' => $this->_params['inf2'],
                    ));
                    break;
                case 'edit':
                    $dataObj->save(array(
                        'id' => $this->_params['id'],
                        'inf1' => $this->_params['inf1'],
                        'inf2' => $this->_params['inf2'],
                    ));
                    break;
                case 'del':
                    $dataObj->delete(array(
                        'id' => explode(',', $this->_params['id'])
                    ));
                    break;
            }
        } catch(Exception $e) {
            $result = $e->getMessage();
        }
        $this->_sendPlainText($result);
    }
}
