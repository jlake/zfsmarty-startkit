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
        $this->_appendJs(array(
            '/js/jqGrid/js/i18n/grid.locale-ja.js',
            '/js/jqGrid/js/jquery.jqGrid.min.js'
        ));
        $this->_appendCss(array(
            '/css/themes/south-street/jquery-ui-1.8.15.custom.css',
            '/js/jqGrid/css/ui.jqgrid.css'
        ));
    }

    public function indexAction()
    {
        $this->_forward('demo1');
    }

    public function demo1Action()
    {
    }

    public function demo1dataAction()
    {
        $demoObj = new Admin_Model_GridDemo();
        $data = $demoObj->getDummyData($this->_params);
        $this->_sendJson($data);
    }

    public function demo2Action()
    {
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
                        'id' => $this->_params['id']
                    ));
                    break;
            }
        } catch(Exception $e) {
            $result = $e->getMessage();
        }
        $this->_sendPlainText($result);
    }
}
